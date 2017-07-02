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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * import controller.
 *
 * @Route("import")
 */
class DefaultController extends Controller
{
 

	/**
     * Creates a new product entity.
     *
     * @Route("/", name="product_import", options={"expose"=true})
     */
    public function importAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('ProductBundle:Product')->getAllProducts();
        $categories = $em->getRepository('CategoryBundle:Category')->findAll();
        $providers = $em->getRepository('ProviderBundle:Provider')->findAll();
        
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('fileExcel', FileType::class, array(
                'label' => 'choisissez un fichier excel(csv)'
            ))->getForm();

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

    /**
     * export product entity.
     *
     * @Route("/export", name="product_export", options={"expose"=true})
     */
    public function ExcelExportAction()
    {
        //get list of product
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('ProductBundle:Product')->getAllProducts();
        //echo "<pre>"; var_dump($products);echo "</pre>";  die();
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
           ->setLastModifiedBy("Giulio De Donato")
           ->setTitle("Office 2005 XLSX Test Document")
           ->setSubject("Office 2005 XLSX Test Document")
           ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
           ->setKeywords("office 2005 openxml php")
           ->setCategory("Test result file");
        $phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue("A1", "ID produit")
           ->setCellValue("B1", "SKU")
           ->setCellValue("C1", "Article")
           ->setCellValue("D1", "Categorie")
           ->setCellValue("E1", "Sous-categorie")
           ->setCellValue("F1", "Description")
           ->setCellValue("G1", "Taille ( inch )")
           ->setCellValue("H1", "Taille ( cm )")
           ->setCellValue("I1", "Couleur")
           ->setCellValue("J1", "Composition")
           ->setCellValue("K1", "Forme")
           ->setCellValue("L1", "Poids")
           ->setCellValue("M1", "# Fournisseur")
           ->setCellValue("N1", "# Emballage")
           ->setCellValue("O1", "Prix unitaire")
           ->setCellValue("P1", "Prix grossiste")
           ->setCellValue("Q1", "Prix MP")
           ->setCellValue("R1", "Prix internet")
           ->setCellValue("S1", "Collection");

        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(40);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('P')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('Q')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('R')->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('S')->setWidth(20);

        $row = 2;
        $cats = array();
        $provs = array();
        $packs = array();
        foreach($products as $product) {
            $phpExcelObject->setActiveSheetIndex(0)
               ->setCellValue('A'.$row, $product->getCodeBar())
               ->setCellValue('B'.$row, $product->getSku())
               ->setCellValue('C'.$row, $product->getName());
               foreach ($product->getCategories() as $cat) { 
                   $cats[]= $cat->getName();
                   $phpExcelObject->setActiveSheetIndex(0)
                   ->setCellValue('D'.$row, $cats[0]);
                   if(!empty($cats[1])){
                        $phpExcelObject->setActiveSheetIndex(0)
                       ->setCellValue('E'.$row, $cats[1]);
                   }else{
                       $phpExcelObject->setActiveSheetIndex(0)
                       ->setCellValue('E'.$row, "");
                   }
               }
               $phpExcelObject->setActiveSheetIndex(0)
               ->setCellValue('F'.$row, $product->getDescription())
               ->setCellValue('G'.$row, $product->getSizeInch())
               ->setCellValue('H'.$row, $product->getSizeCm())
               ->setCellValue('I'.$row, $product->getColor())
               ->setCellValue('J'.$row, $product->getComposition())
               ->setCellValue('K'.$row, $product->getForm())
               ->setCellValue('L'.$row, $product->getWeight());
               foreach ($product->getProviders() as $prov) {
                    $provs[]= $prov->getName();
                    $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('M'.$row, $provs[0]);
                }
                foreach ($product->getPackages() as $pack) {
                    $packs[]= $pack->getName();
                    $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('N'.$row, $packs[0]);
                }
                $phpExcelObject->setActiveSheetIndex(0)
               ->setCellValue('O'.$row, $product->getUnitPrice())
               ->setCellValue('P'.$row, $product->getWholesalePrice())
               ->setCellValue('Q'.$row, $product->getSpecialPrice())
               ->setCellValue('R'.$row, $product->getInternetPrice())
               ->setCellValue('S'.$row, $product->getCollection());
               $row ++;
                unset($cats);
                $cats = array();

        }
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'base-des-articles-mp.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;  
    }



}
