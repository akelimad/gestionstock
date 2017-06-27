<?php

namespace ProviderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/prov")
     */
    public function indexAction()
    {
        return $this->render('ProviderBundle:Default:index.html.twig');
    }
}
