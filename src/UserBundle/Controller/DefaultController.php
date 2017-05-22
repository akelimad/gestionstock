<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery("SELECT u FROM UserBundle:User u where u.deleted_at IS NULL  ORDER BY u.username ASC ");
        $users = $query->getResult();
        return $this->render('UserBundle:User:index.html.twig', array('users' => $users));

    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="user_delete")
     * @Method("PUT")
     */
    public function deleteAction(Request $request , User $user)
    {
        $user->setDeletedAt(new \DateTime());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('user_default_index');
    }
}
