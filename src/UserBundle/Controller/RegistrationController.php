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
            $to=$form->get('email')->getData();
            $nom=$form->get('nom')->getData();
            $username=$form->get('username')->getData();
            $password=$form->get('plainPassword')->getData();

            $subject = "Registration";
             
            $message = "<b>You did it! You registered! </b> <br>";
            $message .= "Hi ".$nom."! You're successfully registered. <br>";
            $message .= "To login, go to: <a href='http://gestion.hubdw.com/login'>login</a>.Thanks!";
            $message .= "login with your username : ".$username." and password : ".$password;
             
            $header = "From:contact@digitalwork.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
             
            $retval = mail ($to,$subject,$message,$header);
             
            if( $retval == true ) {
                $this->get('session')->getFlashBag()->add(
                    'registered',
                    array(
                        'alert' => 'success',
                        'title' => 'Succés! ',
                        'message' => 'Un email de confirmation a été envoyé a : '. $to .'.'
                    )
                );
            }else {
                echo "Message could not be sent...";
            }

            return $this->redirectToRoute('user_default_index');
        }

        return $this->render('registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}