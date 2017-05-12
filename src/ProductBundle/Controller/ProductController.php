<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Product;
use ProductBundle\Entity\ImageProduct;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ProductBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


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
        $products = $em->getRepository('ProductBundle:Product')->findAll();
        
        // foreach($products as $prod){
        //     foreach($prod->getCategory() as $cat){
        //         var_dump($cat->getName());
        //     }
        //     die;
        // }

        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'json_products' => json_encode($products)
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
        $form = $this->createForm('ProductBundle\Form\ProductType', $product);
        $form->handleRequest($request); 
        $em = $this->getDoctrine()->getEntityManager();
        if ($form->isSubmitted() && $form->isValid()){
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
            $em->persist($product);
            try{

            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Add your message in the session
                $this->get("session")->getFlashBag()->add('error', 'PDO Exception :'.$errorMessage);   
            }
            $em->flush();
            return $this->redirectToRoute('product_index');
        }
        return $this->render('product/new.html.twig', array(
            'product' => $product,
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
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

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
}
