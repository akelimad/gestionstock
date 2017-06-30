<?php 

namespace ProviderBundle\Controller;

use ProviderBundle\Entity\Provider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Provider controller.
 *
 * @Route("provider")
 */
class ProviderController extends Controller
{
    /**
     * Lists all provider entities.
     *
     * @Route("/", name="provider_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $providers = $em->getRepository('ProviderBundle:Provider')->getAllProviders();

        return $this->render('provider/index.html.twig', array(
            'providers' => $providers,
        ));
    }

    /**
     * Creates a new provider entity.
     *
     * @Route("/new", name="provider_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $provider = new Provider();
        $form = $this->createForm('ProviderBundle\Form\ProviderType', $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $rateQuality=$request->request->get('star-q');
            $rateQualityPrice=$request->request->get('star-rqp');
            $rateDelivery=$request->request->get('star-dl');
            $rateComm=$request->request->get('star-c');
            $rateRelationship=$request->request->get('star-ep');
            $ring=(($rateQuality+$rateQualityPrice+$rateDelivery+$rateComm+$rateRelationship)*20/5);
            
            $provider->setRateQuality($rateQuality);
            $provider->setRateQualityPrice($rateQualityPrice);
            $provider->setRateDelivery($rateDelivery);
            $provider->setRateCommunication($rateComm);
            $provider->setRatePartnership($rateRelationship);
            $provider->setRing($ring);

            $em->persist($provider);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'addProvider',
                array(
                    'alert' => 'success',
                    'title' => 'success.title',
                    'message' => 'provider.add.flush.success',
                )
            );
            return $this->redirectToRoute('provider_index');
        }

        return $this->render('provider/new.html.twig', array(
            'provider' => $provider,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a provider entity.
     *
     * @Route("/{id}", name="provider_show")
     * @Method("GET")
     */
    public function showAction(Provider $provider)
    {
        $deleteForm = $this->createDeleteForm($provider);

        return $this->render('provider/show.html.twig', array(
            'provider' => $provider,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing provider entity.
     *
     * @Route("/{id}/edit", name="provider_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Provider $provider)
    {
        $deleteForm = $this->createDeleteForm($provider);
        $editForm = $this->createForm('ProviderBundle\Form\ProviderType', $provider);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $rateQuality=$request->request->get('star-q');
            $rateQualityPrice=$request->request->get('star-rqp');
            $rateDelivery=$request->request->get('star-dl');
            $rateComm=$request->request->get('star-c');
            $rateRelationship=$request->request->get('star-ep');
            $ring=(($rateQuality+$rateQualityPrice+$rateDelivery+$rateComm+$rateRelationship)*20/5);

            $provider->setRateQuality($rateQuality);
            $provider->setRateQualityPrice($rateQualityPrice);
            $provider->setRateDelivery($rateDelivery);
            $provider->setRateCommunication($rateComm);
            $provider->setRatePartnership($rateRelationship);
            $provider->setRing($ring);

            $em->persist($provider);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add(
                'editProvider',
                array(
                    'alert' => 'success',
                    'title' => 'success.title',
                    'message' => 'provider.edit.flush.success',
                )
            );
            return $this->redirectToRoute('provider_index');
        }

        return $this->render('provider/edit.html.twig', array(
            'provider' => $provider,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a provider entity.
     *
     * @Route("/{id}/delete", name="provider_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Provider $provider)
    {
        // $form = $this->createDeleteForm($provider);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($provider);
            $em->flush();
        // }
        // $provider->setDeletedAt(new \DateTime());
        // $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('provider_index');
    }

    /**
     * Creates a form to delete a provider entity.
     *
     * @param Provider $provider The provider entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Provider $provider)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('provider_delete', array('id' => $provider->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
