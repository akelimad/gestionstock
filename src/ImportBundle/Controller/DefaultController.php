<?php

namespace ImportBundle\Controller;

use ProductBundle\Entity\Product;
use ProductBundle\Entity\ImageProduct;
use ProductBundle\Entity\ProductLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

	/**
     * Creates a new product entity.
     *
     * @Route("/import", name="product_import", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function importAction(Request $request){
        $product = new Product();
        $productlog = new ProductLog();
        $em = $this->getDoctrine()->getEntityManager();
        $csvFile = $request->request->get("file_excel");
        var_dump($csvFile);die();
        $file->move($this->getParameter('import_csv_directory'),$csvFile);

        return $this->render('product/new.html.twig', array(
            
        ));
    }
}
