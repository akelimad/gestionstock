<?php

namespace ImportBundle\Controller;

use ProductBundle\Entity\Product;
use ProductBundle\Entity\ImageProduct;
use CategoryBundle\Entity\Category;
use ProviderBundle\Entity\Provider;
use PackageBundle\Entity\Package;
use ProductBundle\Entity\ProductLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;



class DefaultController extends Controller
{
 

	/**
     * Creates a new product entity.
     *
     * @Route("/import", name="product_import", options={"expose"=true})

     */
    public function importAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('ProductBundle:Product')->getAllProducts();
        $categories = $em->getRepository('CategoryBundle:Category')->findAll();
        $providers = $em->getRepository('ProviderBundle:Provider')->findAll();
        
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('fileExcel', FileType::class, array('label' => 'choisissez un fichier excel(csv)'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form->get('fileExcel')->getData();
            $name = $file->getClientOriginalName();
            $file->move($this->getParameter('import_csv_directory'),$name);

            $path = $this->getParameter('import_csv_directory')."/".$name;
            
            if(!file_exists($path) || !is_readable($path)) {
                return FALSE;
            }
            
            $header = NULL;
            $delimiter = ";";
            $data = array();
            
            if (($handle = fopen($path, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    if(!$header) {
                        $header = $row;
                    } else {
                        $data[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }
            
            // var_dump($data);die();
            //echo "<pre>"; var_dump($data); echo "</pre>";die();
            // Turning off doctrine default logs queries for saving memory
            //$em->getConnection()->getConfiguration()->setSQLLogger(null);
            
            // Define the size of record, the frequency for persisting the data and the current index of records
            $size = count($data);
            $batchSize = 20;
            $i = 0;
            
            // Starting progress
            // $progress = new ProgressBar($output, $size);
            // $progress->start();
            
            // Processing on each row of data
            
            if($data){
                
                foreach($data as $row) {     
                    $product = new Product();  

                    $product->setName($row["Article"]);
                    $product->setDescription($row["Description"]);
                    $product->setSizeInch($row["Taille ( inch )"]);
                    $product->setSizeCm($row["Taille ( cm )"]);
                    $product->setColor($row["Couleur"]);
                    $product->setComposition($row["Composition"]);
                    $product->setForm($row["Forme"]);
                    $product->setWeight($row["Poids"]);
                    $product->setUnitPrice($row["Prix unitaire"]);
                    $product->setWholesalePrice($row["Prix grossiste"]);
                    $product->setSpecialPrice($row["Prix special"]);
                    $product->setInternetPrice($row["Prix internet"]);
                    $product->setActive("1");

                    $category = $em->getRepository('CategoryBundle:Category')
                       ->findOneByName($row['Categorie']);
                    if($category == null && $row['Categorie'] != ""){
                        $category = new Category;
                        $category->setName($row["Categorie"]);
                        $category->setActive("1");
                        $em->persist($category);
                        $product->addCategories($category);
                    }else{
                        $product->addCategories($category);
                    }
                    
                    $s_category = $em->getRepository('CategoryBundle:Category')
                       ->findOneByName($row['Sous-categorie']);
                    if($s_category == null && $row["Sous-categorie"] != ""){
                        $s_category = new Category;
                        $s_category->setName($row["Sous-categorie"]);
                        $s_category->setParent($category);
                        $s_category->setActive("1");
                        $em->persist($s_category);
                        $product->addCategories($s_category);
                    }else{
                        $product->addCategories($s_category);
                    }
                    
                    $provider = $em->getRepository('ProviderBundle:Provider')
                       ->findOneByName($row['# Fournisseur']);
                    if(!is_object($provider) && $row["# Fournisseur"] != ""){
                        $provider = new Provider;
                        $provider->setName($row["# Fournisseur"]);
                        $provider->setActive("1");
                        $em->persist($s_category);
                        $product->addProviders($provider);
                    }

                    $package = $em->getRepository('PackageBundle:Package')
                       ->findOneByName($row['# Emballage']);
                    if(!is_object($package) && $row["# Emballage"] != ""){
                        $package = new Package;
                        $package->setName($row["# Emballage"]);
                        $package->setActive("1");
                        $em->persist($package);
                        $product->addPackages($package);
                    }

                    
                    $em->persist($product);
                    $i++;
                    //var_dump($category) ;die();
         
                $em->flush();
                } //end foreach 

            }
            
            return $this->redirectToRoute('product_index');
        }


        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'categories' => $categories,
            'providers' => $providers,
            'form' => $form->createView()
        ));
    }
}
