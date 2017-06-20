<?php

namespace CategoryBundle\Controller;

use CategoryBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Category controller.
 *
 * @Route("category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="category_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CategoryBundle:Category')->getAllRootCat();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Lists all category entities.
     *
     * @Route("/subcategory", name="sub_category_index")
     */
    public function sub_indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CategoryBundle:Category')->getAllSubCat();

        return $this->render('category/sub_index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm('CategoryBundle\Form\CategoryType', $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // $catQuery = $em->createQuery("SELECT COUNT(c) FROM 
            //                 CategoryBundle:Category c WHERE c.parent IS NULL");
            // $catCount = $catQuery->getSingleScalarResult();
            // if($catCount < 10){
            //     $code='0'.$catCount;
            // }else{
            //     $code=$catCount;
            // }
            // $category->setCode($code);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
            'is_sub_cat' => false
        ));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/sub_cat", name="subcategorie_new")
     * @Method({"GET", "POST"})
     */
    public function newSubCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('CategoryBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // $sub_catQuery = $em->createQuery("SELECT COUNT(c) FROM 
            //                 CategoryBundle:Category c WHERE c.parent IS NOT NULL");
            // $sub_catCount = $sub_catQuery->getSingleScalarResult();
            // if($sub_catCount < 10){
            //     $code='00'.$sub_catCount;
            // }elseif ($sub_catCount > 10 && $sub_catCount < 1000) {
            //     $code='0'.$sub_catCount;
            // }
            // $category->setCode($code);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
            'is_sub_cat' => true
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('CategoryBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // $catcodeQuery = $em->createQuery("SELECT c.code FROM 
            //             CategoryBundle:Category c WHERE c.id = ".$category->getId());
            // $catCode = $catcodeQuery->getSingleScalarResult();

            // $catfromcodebarQuery = $em->createQuery("SELECT p.codeBar from ProductBundle:Product p, CategoryBundle:Category c where c=p");
            // $cat_code = $catfromcodebarQuery->getResult();
            // var_dump($cat_code); die();
            // if(empty($catCode)){
            //     $code=substr($cat_code, 1, 2);
            // }else{
            //     $code=$catCode;
            // }

            // $category->setCode($code);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'is_sub_cat' => false
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/sub_cat/{id}/edit", name="sub_category_edit")
     * @Method({"GET", "POST"})
     */
    public function editSubAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('CategoryBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // $sub_catcodeQuery = $em->createQuery("SELECT c.code FROM 
            //             CategoryBundle:Category c WHERE c.id = ".$category->getId());
            // $sub_catCode = $sub_catcodeQuery->getSingleScalarResult();

            // $sub_catfromcodebarQuery = $em->createQuery("SELECT p.codeBar from ProductBundle:Product p, CategoryBundle:Category c where c=p and p.codeBar is not null");
            // $sub_cat_code = $sub_catfromcodebarQuery->getSingleScalarResult();
            // //var_dump($sub_cat_code);die();
            // if(empty($sub_catCode) and !empty($sub_cat_code)){
            //     $code=substr($sub_cat_code, 3, 3);
            // }else{
            //     $code=$sub_catCode;
            // }

            //$category->setCode($code);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'is_sub_cat' => true
        ));
    }


    /**
     * Deletes a category entity.
     *
     * @Route("/{id}", name="category_delete" , options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        // $form = $this->createDeleteForm($category);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        // }
        // $category->setDeletedAt(new \DateTime());
        // $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
