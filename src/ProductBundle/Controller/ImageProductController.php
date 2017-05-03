<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\ImageProduct;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Imageproduct controller.
 *
 * @Route("imageproduct")
 */
class ImageProductController extends Controller
{
    /**
     * Lists all imageProduct entities.
     *
     * @Route("/", name="imageproduct_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $imageProducts = $em->getRepository('ProductBundle:ImageProduct')->findAll();

        return $this->render('imageproduct/index.html.twig', array(
            'imageProducts' => $imageProducts,
        ));
    }

    /**
     * Creates a new imageProduct entity.
     *
     * @Route("/new", name="imageproduct_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $imageProduct = new Imageproduct();
        $form = $this->createForm('ProductBundle\Form\ImageProductType', $imageProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imageProduct);
            $em->flush();

            return $this->redirectToRoute('imageproduct_show', array('id' => $imageProduct->getId()));
        }

        return $this->render('imageproduct/new.html.twig', array(
            'imageProduct' => $imageProduct,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a imageProduct entity.
     *
     * @Route("/{id}", name="imageproduct_show")
     * @Method("GET")
     */
    public function showAction(ImageProduct $imageProduct)
    {
        $deleteForm = $this->createDeleteForm($imageProduct);

        return $this->render('imageproduct/show.html.twig', array(
            'imageProduct' => $imageProduct,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing imageProduct entity.
     *
     * @Route("/{id}/edit", name="imageproduct_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ImageProduct $imageProduct)
    {
        $deleteForm = $this->createDeleteForm($imageProduct);
        $editForm = $this->createForm('ProductBundle\Form\ImageProductType', $imageProduct);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('imageproduct_edit', array('id' => $imageProduct->getId()));
        }

        return $this->render('imageproduct/edit.html.twig', array(
            'imageProduct' => $imageProduct,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a imageProduct entity.
     *
     * @Route("/{id}", name="imageproduct_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ImageProduct $imageProduct)
    {
        $form = $this->createDeleteForm($imageProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($imageProduct);
            $em->flush();
        }

        return $this->redirectToRoute('imageproduct_index');
    }

    /**
     * Creates a form to delete a imageProduct entity.
     *
     * @param ImageProduct $imageProduct The imageProduct entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ImageProduct $imageProduct)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('imageproduct_delete', array('id' => $imageProduct->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
