<?php

namespace ProviderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class ProviderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', null, array('label' => 'provider.label.name'))
        ->add('lastName', null, array('label' => 'provider.label.lastName'))
        ->add('society', null, array('label' => 'provider.label.society'))
        ->add('activity', null, array('label' => 'provider.label.activity'))
        ->add('address', null, array('label' => 'provider.label.address'))
        ->add('tel', null, array('label' => 'provider.label.tel'))
        ->add('email', EmailType::class, array(
            'required' => false,
            'label'   => 'provider.label.email'
        ))
        ->add('active', null, array('label' => 'provider.label.status'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProviderBundle\Entity\Provider'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'providerbundle_provider';
    }


}
