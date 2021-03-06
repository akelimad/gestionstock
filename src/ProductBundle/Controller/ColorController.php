<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Color;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Color controller.
 *
 * @Route("color")
 */
class ColorController extends Controller
{
    /**
     * Lists all color entities.
     *
     * @Route("/", name="color_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $colors = $em->getRepository('ProductBundle:Color')->findAll();

        return $this->render('color/index.html.twig', array(
            'colors' => $colors,
        ));
    }

    /**
     * Creates a new color entity.
     *
     * @Route("/new", name="color_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $color = new Color();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('ProductBundle\Form\ColorType', $color);
        $form->handleRequest($request);
        $categories = $em->getRepository('CategoryBundle:Category')->getAllRootCat();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $color->setCode($_POST['color']);
            $em->persist($color);
            $em->flush();

            return $this->redirectToRoute('color_index');
        }

        return $this->render('color/new.html.twig', array(
            'color' => $color,
            'categories' => $categories,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a color entity.
     *
     * @Route("/{id}", name="color_show")
     * @Method("GET")
     */
    public function showAction(Color $color)
    {
        $deleteForm = $this->createDeleteForm($color);

        return $this->render('color/show.html.twig', array(
            'color' => $color,
            'categories' => $categories,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing color entity.
     *
     * @Route("/{id}/edit", name="color_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Color $color)
    {
        $deleteForm = $this->createDeleteForm($color);
        $editForm = $this->createForm('ProductBundle\Form\ColorType', $color);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('CategoryBundle:Category')->getAllRootCat();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $color->setCode($_POST['color']);
            $em->persist($color);
            $em->flush();

            return $this->redirectToRoute('color_index');
        }

        return $this->render('color/edit.html.twig', array(
            'color' => $color,
            'categories' => $categories,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a color entity.
     *
     * @Route("/{id}", name="color_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Color $color)
    {
        $form = $this->createDeleteForm($color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($color);
            $em->flush();
        }

        return $this->redirectToRoute('color_index');
    }

    /**
     * Creates a form to delete a color entity.
     *
     * @param Color $color The color entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Color $color)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('color_delete', array('id' => $color->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
