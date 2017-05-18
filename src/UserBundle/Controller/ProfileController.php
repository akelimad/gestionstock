<?php 

namespace UserBundle\Controller;

use UserBundle\Form\ProfileType;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
//use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProfileController extends Controller
{
    /**
     * @Route("/user/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);

        // if (!is_object($user)) {
        //     throw new AccessDeniedException('This user does not have access to this section.');
        // }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $this->createForm(ProfileType::class, $user);
        $form->setData($user);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            // $session = $this->getRequest()->getSession();
            // $session->getFlashBag()->add('message', 'Successfully updated');
            return $this->redirectToRoute('user_default_index');

        }

        return $this->render('profile/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}