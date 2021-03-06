<?php

namespace CategoryBundle\Form;

use ProductBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', null, array('label' => 'category.label.name'))
        ->add('code')
        ->add('description')
        ->add('parent', EntityType::class, array(
            'class'=>'CategoryBundle:Category', 
            'choice_label'=>'name', 
            'required'=> false,
            'query_builder' => function ($er) {
               return $er->createQueryBuilder('c')->distinct('c.name')->where("c.parent is NULL ");
            },
            'label' => 'category.label.parent'
        ))
        ->add('active', null, array('label' => 'label.status'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CategoryBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'categorybundle_category';
    }


}
