<?php

namespace ProductBundle\Form;

use ProductBundle\Entity\Product;
use ProductBundle\Entity\ImageProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
        ->add('name')
        ->add('description')
        ->add('sizeInch')
        ->add('sizeCm')
        ->add('color')
        ->add('composition')
        ->add('form')
        ->add('weight')
        ->add('unitPrice')
        ->add('wholesalePrice')
        ->add('specialPrice')
        ->add('internetPrice')
        ->add('active')
        ->add('images', FileType::class, array(
           'multiple' => true,
           'data_class' => null,
           'required' => false
        ))
        // ->add('categories',EntityType::class, array(
        //     'class'=>'CategoryBundle:Category', 
        //     'choice_label'=>'name', 
        //     'query_builder' => function ($er) {
        //        return $er->createQueryBuilder('c');
        //     },
        //     'multiple'=> true,
        // ))
        ->add('packages', EntityType::class, array(
            'class'=>'PackageBundle:Package', 
            'choice_label'=>'name', 
            'multiple'=>true,
            'required' => false
        ))
        ->add('providers',EntityType::class, array(
            'class'=>'ProviderBundle:Provider', 
            'choice_label'=>'name',
            'multiple'=>true,
            'required' => false
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProductBundle\Entity\Product'
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'productbundle_product';
    }


}
