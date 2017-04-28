<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     */
    public function indexAction()
    {
    	$repository = $this
    		->getDoctrine()
			->getRepository('UserBundle:User');
		$users = $repository->findAll();
        return $this->render('UserBundle:User:index.html.twig', array('users' => $users));
    }
}
