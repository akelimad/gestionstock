<?php

namespace PackageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', null, array('label' => 'package.label.name'))
        ->add('size', null, array('label' => 'package.label.size'))
        ->add('cbm', null, array('label' => 'package.label.cbm'))
        ->add('marineCost', null, array('label' => 'package.label.marineCost'))
        ->add('airCost', null, array('label' => 'package.label.airCost'))
        ->add('active', null, array('label' => 'package.label.status'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PackageBundle\Entity\Package'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'packagebundle_package';
    }


}
