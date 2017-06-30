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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
        ->add('name', null, array('label' => 'product.label.name') ) 
        ->add('description', null, array('label' => 'product.label.description'))
        ->add('sku')
        ->add('sizeInch', null, array('label' => 'product.label.sizeInch'))
        ->add('sizeCm', null, array('label' => 'product.label.sizeCm'))
        ->add('color', ChoiceType::class, array(
            'choices' => array(
                'Black' => '#000000',
                'Gray' => '#808080',
                'DarkGray' => '#A9A9A9',
                'LightGray' => '#D3D3D3',
                'White' => '#FFFFFF',
                'Aquamarine' => '#7FFFD4',
                'Blue' => '#0000FF',
                'Navey' => '#000080',
                'Purple' => '#800080',
                'DeepPink' => '#FF1493',
                'Violet' => '#EE82EE',
                'Pink' => '#FFC0CB',
                'DarkGreen' => '#006400',
                'Green' => '#008000',
                'YellowGreen' => '#9ACD32',
                'Yellow' => '#FFFF00',
                'Orange' => '#FFA500',
                'Red' => '#FF0000',
                'Brown' => '#A52A2A',
                'BurlyWood' => '#DEB887',
                'Beige' => '#F5F5DC',
            ),
            'label' => 'product.label.color'
        ))
        ->add('composition', null, array('label' => 'product.label.composition'))
        ->add('form', null, array('label' => 'product.label.form'))
        ->add('weight', null, array('label' => 'product.label.weight'))
        ->add('unitPrice', null, array('label' => 'product.label.unitPrice'))
        ->add('wholesalePrice', null, array('label' => 'product.label.wholesalePrice'))
        ->add('specialPrice', null, array('label' => 'product.label.specialPrice'))
        ->add('internetPrice', null, array('label' => 'product.label.internetPrice'))
        ->add('collection', null, array('label' => 'product.label.collection'))
        ->add('status', ChoiceType::class, array(
            'choices' => array(
                'product.status.inventory' => 'Inventaire',
                'product.status.underdev' => 'Produit en développement',
                'product.status.available'   => 'Disponible à la vente',
                'product.status.expired' => 'Fin de cycle',
                'product.status.inactive' => 'Inactif'
            ),
            'label' => 'product.label.status'
        ))
        ->add('currency', ChoiceType::class, array(
            'choices' => array(
                'EUR' => 'EUR',
                'USD' => 'USD',
                'MAD'=> 'MAD',
            )
        ))
        ->add('images', FileType::class, array(
           'multiple' => true,
           'data_class' => null,
           'required' => false
        ))
        // ->add('categories',EntityType::class, array(
        //     'class'=>'CategoryBundle:Category', 
        //     'choice_label'=>'name',
        //     'query_builder' => function ($er) {
        //        return $er->createQueryBuilder('c')->where("c.deleted_at IS NULL AND c.parent IS NULL");
        //     },
        //     'multiple'=> false,
        //     'required' => false,
        //     'mapped' => false,
        //     'placeholder' => 'Vuillez choisir une catégorie'
        // ))
        // ->add('subcat',EntityType::class, array(
        //     'class'=>'CategoryBundle:Category', 
        //     'choice_label'=>'name', 
        //     'query_builder' => function ($er) {
        //        return $er->createQueryBuilder('c')->where("c.deleted_at IS NULL AND c.parent IS NOT NULL");
        //     },
        //     'mapped' => false,
        //     'multiple'=> false,
        //     //'group_by' => 'parent',
        //     //'required' => false,
        //     'placeholder' => 'Sous categorie'
        // ))

        ->add('packages', EntityType::class, array(
            'class'=>'PackageBundle:Package', 
            'choice_label'=>'name', 
            'multiple'=>true,
            'required' => false,
            'query_builder' => function ($er) {
               return $er->createQueryBuilder('p')->where("p.deleted_at IS NULL");
            },
            'label' => 'product.label.package'
        ))
        ->add('providers',EntityType::class, array(
            'class'=>'ProviderBundle:Provider', 
            'choice_label'=>'name',
            'multiple'=>true,
            'required' => false,
            'query_builder' => function ($er) {
               return $er->createQueryBuilder('p')->where("p.deleted_at IS NULL");
            },
            'label' => 'product.label.provider'
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
