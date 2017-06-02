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
        $providers = $em->getRepository('ProviderBundle:Provider')->findAll();

        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('fileExcel', FileType::class, array('label' => 'choisissez un fichier excel(csv)'))
            ->getForm();

        $form->handleRequest($request);

        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'categories' => $categories,
            'sub_categories' => $sub_categories,
            'providers' => $providers,
            'form' => $form->createView()
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
            $cat_id=$request->request->get('categories');
            $sub_cat_id= $request->request->get('s_categories');
            $Category=$em->getRepository('CategoryBundle:Category')->findById( array($cat_id, $sub_cat_id));
            //var_dump($Category);die;
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
            $product->setImages($images);
            $product->setCategories($Category);
            $productlog->setProduct($product);
            $productlog->setUser($this->getUser());
            $productlog->setAction("Add");
            // $catsProd=array();
            // $cats= array();
            // $cats[]=$request->request->get('cat_product');
            // foreach ($cats as $cat) {
            //     $catsProd[]=$cat;
            // }
            // $product->setCategories($catsProd);
            //$product->setCategories($catsProd);
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
     * @Route("/{id}", name="product_show" ,options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        return $this->render('product/show.html.twig', array(
            'id'      => $product->getId(),
            'product' => $product,
        ));
    }
    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
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
        //$s_categories = $em->getRepository('CategoryBundle:Category')->findBy(array('parent' => $prod_cat[0]));
        //var_dump($product->getCategories()); die();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $cat_id=$request->request->get('categories');
            $sub_cat_id= $request->request->get('s_categories');
            $Category=$em->getRepository('CategoryBundle:Category')->findById( array($cat_id, $sub_cat_id));
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
            $product->setImages($images);
            $product->setCategories($Category);
            $productlog->setProduct($product);
            $productlog->setUser($this->getUser());
            $productlog->setAction("Edit");
            $em->persist($productlog);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('product_index');
        }
        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            //'s_categories' => $categories,
            'cat_selected' => $prod_cat[0],
            //'sub_cat_selected' => $prod_cat[1],
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="product_delete")
     * @Method("PUT")
     */
    public function deleteAction(Request $request, Product $product)
    {
        // $form = $this->createDeleteForm($product);
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $em = $this->getDoctrine()->getManager();
        //     $em->remove($product);
        //     $em->flush();
        // }
        $productlog = new ProductLog();
        $em = $this->getDoctrine()->getEntityManager();
        
        $productlog->setProduct($product);
        $productlog->setUser($this->getUser());
        $productlog->setAction("Delete");
        $productlog->setDeletedAt(new \DateTime());
        $em->persist($productlog);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('product_index');
        //return new Response("product deleted");
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
            // $response = array();
            // foreach ($products as $p) {
            //     $response[] = array(
            //         'id' => $p->getId(),
            //         'name' => $p->getName(),                   
            //     );
            // }
            // return new JsonResponse(json_encode($response));
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
        
        // $em = $this->getDoctrine()->getManager();
        // $s_categories = $em->getRepository('CategoryBundle:Category')->getAllSubCat($id);

        // var_dump($s_categories);die();
        //return new JsonResponse(array('data' => $s_categories));
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