<?php 

namespace UserBundle\Controller;

use UserBundle\Form\RegistrationType;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        // $auth_checker = $this->get('security.authorization_checker');
        // $isRoleSuperAdmin = $auth_checker->isGranted('ROLE_SUPER_ADMIN');
        // $roles=array();
        // $roles=$user->getRoles();
        if ($form->isSubmitted() && $form->isValid() ) {
            // if($roles[0]=="ROLE_SUPER_ADMIN" && $isRoleAdmin==true){
            //     throw new AccessDeniedException('Only super admin can add super admin roles !!!!');
            // }else{

                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                

                // 4) save the User!
                $em = $this->getDoctrine()->getManager(); 
                $em->persist($user);
                $em->flush();
            //}
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            // $to=$form->get('email')->getData();
            // $name=$form->get('username')->getData();
            // $message = new \Swift_Message('Hello Email');
            //         $message->setFrom('akel.dev@gmail.com')
            //         ->setTo($to)
            //         ->setBody(
            //             $this->renderView(
            //                 // app/Resources/views/Emails/registration.html.twig
            //                 'email/registration.html.twig',
            //                 array('name' => $name)
            //             ),
            //             'text/html'
            //         );
            //     $this->get('mailer')->send($message);
            $to      = 'akel.dev@gmail.com';
            $subject = 'the subject';
            $message = 'hello';
            $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: webmaster@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);

            return $this->redirectToRoute('user_default_index');
        }

        return $this->render('registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}