<?php 

namespace UserBundle\Form;

use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('roles', ChoiceType::class, array(
                'choices'   => [
                    #'ROLE SUPER ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ROLE ADMIN' =>       'ROLE_ADMIN',
                    'ROLE USER' =>        'ROLE_USER'
                ],
                'required'  => true,
                'multiple' => true
            ))
            ->add('enabled');
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName()
    {
        return 'app_user_registration';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'UserBundle\Entity\User',
        ]);
    }
}
