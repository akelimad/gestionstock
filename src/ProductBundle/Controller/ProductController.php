<?php
namespace ProductBundle\Controller; 
use ProductBundle\Entity\Product;
use ProductBundle\Entity\ImageProduct;
use ProductBundle\Entity\ProductLog;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ProductBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    private $ImageProduct;
    public function __construct()
    {
        $this->ImageProduct = new ImageProduct();
    }
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")

     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('ProductBundle:Product')->getAllProducts();
        $categories = $em->getRepository('CategoryBundle:Category')->getAllRootCat();
        $sub_categories = $em->getRepository('CategoryBundle:Category')->getAllSubCat();
        $providers = $em->getRepository('ProviderBundle:Provider')->findBy(array('deleted_at' => NULL));

        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'categories' => $categories,
            'sub_categories' => $sub_categories,
            'providers' => $providers,
        ));
    }
    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $product = new Product();
        $productlog = new ProductLog();
        $form = $this->createForm('ProductBundle\Form\ProductType', $product);
        $form->handleRequest($request); 
        $categories = $em->getRepository('CategoryBundle:Category')->getAllRootCat();

        $em = $this->getDoctrine()->getEntityManager();
        if ($form->isSubmitted() && $form->isValid()){
            //cat and subcat selected
            $cat_id=$request->request->get('categories');
            $sub_cat_id= $request->request->get('s_categories');
            //add product to cat selected
            $Category=$em->getRepository('CategoryBundle:Category')->findById( array($cat_id, $sub_cat_id));
            //get code of cat and subcat selected
            if(!empty($cat_id)){
              $query=$em->createQuery('SELECT c.code FROM CategoryBundle:Category c 
              WHERE c.id = '.$cat_id);
              $catCode = $query->getSingleScalarResult();
              //var_dump($catCode); die();
              $getcatcode = $catCode;
            }

            if(!empty($sub_cat_id)){
              $query1=$em->createQuery('SELECT c.code FROM CategoryBundle:Category c 
                WHERE c.id = '.$sub_cat_id);
              $subcatCode = $query1->getSingleScalarResult();
              if(!empty($subcatCode)){
                $getsubcatCode = $subcatCode;
              }else{
                $getsubcatCode = "000";
              }
            }else{
              $getsubcatCode = "000";
            }
            $countryCode="6";
            $prodQuery = $em->createQuery("SELECT COUNT(p) FROM 
                            ProductBundle:Product p");
            $productCount = $prodQuery->getSingleScalarResult();
            $productCount +=1;
            if($productCount < 10){
              $prodCode = '000000'.$productCount; 
            }elseif($productCount < 100){
              $prodCode = '00000'.$productCount; 
            }elseif($productCount < 1000){
              $prodCode = '0000'.$productCount; 
            }elseif($productCount < 10000){
              $prodCode = '000'.$productCount; 
            }elseif($productCount < 100000){
              $prodCode = '00'.$productCount; 
            }elseif($productCount < 1000000){
              $prodCode = '0'.$productCount; 
            }else{
              $prodCode = $productCount; 
            }
            $serialNumber=$prodCode;
            
            $files = $product->getImages();

            $images = array();
            if($files != null) {
                $key = 0;
                foreach ($files as $file){
                    $fileName = $file->getClientOriginalName();
                    $file->move($this->getParameter('images_directory'),$fileName);
                    $imageProduct  = new ImageProduct();
                    $imageProduct->setProduct($product);
                    $imageProduct->setPath($fileName);
                    $images[] = $imageProduct;
                }
            }
            if($getcatcode != null ){
              $productCodeBar=$countryCode.$getcatcode.$getsubcatCode.$serialNumber;
            }else{
              $productCodeBar=$countryCode.'00'.$getsubcatCode.$serialNumber;
            }

            $product->setCodeBar($productCodeBar);
            $product->setImages($images);
            $product->setCategories($Category);

            $productlog->setProduct($product);
            $productlog->setUser($this->getUser());
            $productlog->setAction("Add");

            $productlog->setUnitPrice($product->getUnitPrice());
            $productlog->setWholesalePrice($product->getWholesalePrice());
            $productlog->setSpecialPrice($product->getSpecialPrice());
            $productlog->setInternetPrice($product->getInternetPrice());

            $em->persist($product);
            $em->persist($productlog);
            $em->flush();
            return $this->redirectToRoute('product_index');
        }
        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a product entity.
     *
     * @Route("/details/{id}", name="product_show" ,options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $priceslog = $em->getRepository('ProductBundle:Product')->getPricesLog($product->getId());
      //echo '<pre>' ; print_r($priceslog); echo '</pre>' ; die();
      return $this->render('product/show.html.twig', array(
          'id'      => $product->getId(),
          'product' => $product,
          'priceslog' => $priceslog,
      ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $productlog = new ProductLog();
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('CategoryBundle:Category')->getAllRootCat();

        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('ProductBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);
        $prod_cat=array();
        foreach ($product->getCategories() as $cat) {
            $prod_cat[]=$cat->getId();
        }
        if(!empty($prod_cat[1])){
          $sub_cat_selected=$prod_cat[1];
        }else{
          $sub_cat_selected=0;
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $cat_id=$request->request->get('categories');
            $sub_cat_id= $request->request->get('s_categories');
            $Category=$em->getRepository('CategoryBundle:Category')->findById( array($cat_id, $sub_cat_id));

            if(!empty($cat_id)){
              $query=$em->createQuery('SELECT c.code FROM CategoryBundle:Category c 
              WHERE c.id = '.$cat_id);
              $catCode = $query->getSingleScalarResult();
              $getcatcode = $catCode;
            }

            if(!empty($sub_cat_id)){
              $query1=$em->createQuery('SELECT c.code FROM CategoryBundle:Category c 
                WHERE c.id = '.$sub_cat_id);
              $subcatCode = $query1->getSingleScalarResult();
              if($subcatCode != null){
                $getsubcatCode = $subcatCode;
              }else{
                $getsubcatCode = "000";
              }
            }else{
              $getsubcatCode = "000";
            }
            //var_dump($subcatCode); die();
            $countryCode="6";

            $prodCodeQuery = $em->createQuery("SELECT p.codeBar FROM 
                            ProductBundle:Product p where p.id =".$product->getId());
            $productCode = $prodCodeQuery->getSingleScalarResult();
            if(empty($productCode)){
              $productCount = $product->getId();
              if($productCount < 10){
                $prodCode = '000000'.$productCount; 
              }elseif($productCount < 100){
                $prodCode = '00000'.$productCount; 
              }elseif($productCount < 1000){
                $prodCode = '0000'.$productCount; 
              }elseif($productCount < 10000){
                $prodCode = '000'.$productCount; 
              }elseif($productCount < 100000){
                $prodCode = '00'.$productCount; 
              }elseif($productCount < 1000000){
                $prodCode = '0'.$productCount; 
              }else{
                $prodCode = $productCount; 
              }
            }else{
              $prodCode = substr($productCode, -7);
            }
             
            
            $serialNumber=$prodCode;

            $files = $product->getImages();
            $images = array();
            if($files != null) {
                $key = 0;
                foreach ($files as $file){
                    $fileName = $file->getClientOriginalName();
                    $file->move($this->getParameter('images_directory'),$fileName);
                    $imageProduct  = new ImageProduct();
                    $imageProduct->setProduct($product);
                    $imageProduct->setPath($fileName);
                    $images[] = $imageProduct;
                }
            }
            if($getcatcode != null ){
              $productCodeBar=$countryCode.$getcatcode.$getsubcatCode.$serialNumber;
            }else{
              $productCodeBar=$countryCode.'00'.$getsubcatCode.$serialNumber;
            }
            $product->setCodeBar($productCodeBar);
            $product->setImages($images);
            $product->setCategories($Category);

            $productlog->setProduct($product);
            $productlog->setUser($this->getUser());
            $productlog->setAction("Edit");

            $productlog->setUnitPrice($product->getUnitPrice());
            $productlog->setWholesalePrice($product->getWholesalePrice());
            $productlog->setSpecialPrice($product->getSpecialPrice());
            $productlog->setInternetPrice($product->getInternetPrice());

            $em->persist($product);
            $em->persist($productlog);
            $em->flush();
            return $this->redirectToRoute('product_index');
        }
        $url = $this->generateUrl('product_show',array('id' => $product->getId()));
        $this->get('session')->getFlashBag()->add(
            'editProduct',
            array(
                'alert' => 'success',
                'title' => 'success.title',
                'message' => 'product.edit.flush.success'
            )
        );
        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            //'s_categories' => $categories,
            'cat_selected' => $prod_cat[0],
            'sub_cat_selected' => $sub_cat_selected ,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    
    /**
     * Deletes a product entity.
     *
     * @Route("/{id}/desactivate", options={"expose"=true}, name="product_desactivate")
     * @Method("PUT")
     */
    public function desactivateAction(Request $request, Product $product)
    {
        $productlog = new ProductLog();
        $em = $this->getDoctrine()->getEntityManager();
        
        $productlog->setProduct($product);
        $productlog->setUser($this->getUser());
        $productlog->setAction("Desactivate");
        $productlog->setDeletedAt(new \DateTime());
        $em->persist($productlog);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('product_index'); 
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('product_desactiveted'); 
    }

    /**
     * Finds and displays a removed product entity.
     *
     * @Route("/trash", name="product_desactiveted" ,options={"expose"=true})
     * @Method("GET")
     */
    public function trashAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('ProductBundle:Product')->getAllTrashedProducts();
        return $this->render('product/trash.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}/revert", options={"expose"=true}, name="product_revert")
     * @Method("PUT")
     */
    public function revertAction(Request $request, Product $product)
    {
        $productlog = new ProductLog();
        $em = $this->getDoctrine()->getEntityManager();
        
        $productlog->setProduct($product);
        $productlog->setUser($this->getUser());
        $productlog->setAction("revert");
        $productlog->setDeletedAt(NULL);
        $em->persist($productlog);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('product_index'); 
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Lists searched entities.
     *
     * @Route("/cat/{category_id}", name="filter-by-cat" ,options={"expose"=true})
     * @Method("GET")
     */
    public function FilterByCatAction($category_id)
    {
            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('CategoryBundle:Category')->findAll();
            $category = $this->getDoctrine()
            ->getRepository('CategoryBundle:Category')
            ->find($category_id);
            $products = $category->getProduct();
                return $this->render('product/results.html.twig', array(
                'products' => $products,
            ));
    }

    /**
     * Lists searched entities.
     *
     * @Route("/subcat/{sub_category_id}", name="filter-by-sub-cat" ,options={"expose"=true})
     * @Method("GET")
     */
    public function FilterBySubCatAction($sub_category_id)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CategoryBundle:Category')->getAllSubCat();
        $sub_category = $this->getDoctrine()
        ->getRepository('CategoryBundle:Category')
        ->find($sub_category_id);
        $products = $sub_category->getProduct();
        return $this->render('product/results.html.twig', array(
            'products' => $products,
        ));  
    }

    /**
     * Lists searched entities.
     *
     * @Route("/prov/{provider_id}", name="filter-by-prov" ,options={"expose"=true})
     * @Method("GET")
     */
    public function FilterByProvAction($provider_id)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CategoryBundle:Category')->findAll();
        $providers = $em->getRepository('ProviderBundle:Provider')->findAll();
        $provider = $this->getDoctrine()
        ->getRepository('ProviderBundle:Provider')
        ->find($provider_id);
        $products = $provider->getProduct();
        return $this->render('product/results.html.twig', array(
              'products' => $products,
        ));
        
    }

    /**
     * @Route("/new/subcategories", name="ajax_subcategories", options={"expose"=true})
     * @Method("GET")
     */
    public function ajaxSubCategoriesAction(Request $request)
    {
      $id = $request->get('id');
      $em = $this->getDoctrine()->getManager();
      $entities = $em->getRepository('CategoryBundle:Category')->findBy(array('parent' => $id));
      $output = array();
        foreach ($entities as $member) {
          $output[] = array(
              'id' => $member->getId(),
              'name' => $member->getName(),
          );
        }
      $response = new Response();
      $response->headers->set('Content-Type', 'application/json');
      $response->setContent(json_encode($output));
      return $response;
    }

}