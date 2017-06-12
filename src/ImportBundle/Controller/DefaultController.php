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
                        $data[] = $row;
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
            //"codebar" 0
            //"Article" 1
            // ....
            if($data){
                
                foreach($data as $row) {  
                    $product = new Product();  

                    $product->setCodeBar($row[0]);
                    $product->setName($row[1]);
                    $product->setDescription($row[4]);
                    $product->setSizeInch($row[5]);
                    $product->setSizeCm($row[6]);
                    $product->setColor($row[7]);
                    $product->setComposition($row[8]);
                    $product->setForm($row[9]);
                    $product->setWeight($row[10]);
                    $product->setUnitPrice($row[14]);
                    $product->setWholesalePrice($row[15]);
                    $product->setSpecialPrice($row[16]);
                    $product->setInternetPrice($row[17]);
                    $product->setActive("1");

                    $category = $em->getRepository('CategoryBundle:Category')
                       ->findOneByName($row[2]);
                    if($category == null && $row[2] != ""){
                        $category = new Category;
                        $category->setName($row[2]);
                        $category->setActive("1");
                        $em->persist($category);
                        $product->addCategories($category);
                    }else{
                        $product->addCategories($category);
                    }
                    
                    $s_category = $em->getRepository('CategoryBundle:Category')
                       ->findOneByName($row[3]);
                    if($s_category == null && $row[3] != ""){
                        $s_category = new Category;
                        $s_category->setName($row[3]);
                        $s_category->setParent($category);
                        $s_category->setActive("1");
                        $em->persist($s_category);
                        $product->addCategories($s_category);
                    }else{
                        $product->addCategories($s_category);
                    }
                    
                    $provider = $em->getRepository('ProviderBundle:Provider')
                       ->findOneByName($row[12]);
                    if(!is_object($provider) && $row[12] != ""){
                        $provider = new Provider;
                        $provider->setName($row[12]);
                        $provider->setActive("1");
                        $em->persist($s_category);
                        $product->addProviders($provider);
                    }

                    $package = $em->getRepository('PackageBundle:Package')
                       ->findOneByName($row[13]);
                    if(!is_object($package) && $row[13] != ""){
                        $package = new Package;
                        $package->setName($row[13]);
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


        return $this->render('dataExcel/import.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
