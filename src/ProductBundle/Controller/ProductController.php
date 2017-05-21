<?php

namespace ProductBundle\Controller; 

use ProductBundle\Entity\Product;
use ProductBundle\Entity\ImageProduct;
use ProductBundle\Entity\ProductLog;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ProductBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



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
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('ProductBundle:Product')->getAllProducts();
        $categories = $em->getRepository('CategoryBundle:Category')->findAll();
        $providers = $em->getRepository('ProviderBundle:Provider')->findAll();
        // foreach($products as $prod){
        //     foreach($prod->getCategory() as $cat){
        //         var_dump($cat->getName());
        //     }
        //     die;
        // }

        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'categories' => $categories,
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
        $product = new Product();
        $productlog = new ProductLog();
        $form = $this->createForm('ProductBundle\Form\ProductType', $product);
        $form->handleRequest($request); 
        $em = $this->getDoctrine()->getEntityManager();

        $em1 = $this->container->get('doctrine');
        $categories = $em1->getRepository('CategoryBundle:Category')->getAllRootCategories();

        if ($form->isSubmitted() && $form->isValid()){
            //var_dump($request->request->all());die();
            var_dump($form->getData()); die();
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

            //     foreach($_POST['cat_product'] as $cat){
            //         var_dump($cat);
            //     }
            // die();

            $product->setImages($images);
            //$product->setCategories($categories);
            $productlog->setProduct($product);
            $productlog->setUser($this->getUser());
            $productlog->setAction("Add");


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
        //$deleteForm = $this->createDeleteForm($product);

        // $response = new Response(json_encode($products));
        // $response->headers->set('Content-Type', 'application/json');
        // return $response;
        //return new JsonResponse(array('products' => $product));
         $product=array('test'=>'test');
         $response = new Response(array('data'=>json_encode($product)));
         return $response;
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
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('ProductBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);
        //var_dump($product->getImages()); die();
        if ($editForm->isSubmitted() && $editForm->isValid()) {

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
            $productlog->setProduct($product);
            $productlog->setUser($this->getUser());
            $productlog->setAction("Edit");
            $em->persist($productlog);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
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
        $providers = $em->getRepository('ProviderBundle:Provider')->findAll();
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


}
