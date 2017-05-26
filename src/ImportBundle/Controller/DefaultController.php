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
                    $category = new Category;
                    $provider = new Provider;
                    $package = new Package;
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
                    $product->setObservations($row["Observations"]);
                    $product->setActive("1");

                    $category->setName($row["Categorie"]);
                    $category->setActive("1");

                    $provider->setName($row["# Fournisseur"]);
                    $provider->setActive("1");
                    
                    $package->setName($row["# Emballage"]);
                    $package->setActive("1");
                    
                    $em->persist($product);
                    $em->persist($category);
                    $em->persist($provider);
                    $em->persist($package);
         
                    $i++;
         
                } //end foreach 
                $em->flush();
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
