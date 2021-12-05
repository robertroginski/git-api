<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GitCompareType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first', TextType::class, array(
                'label' => 'Git repo #1',
                'required' => true,
                'attr' => [
                    'placeholder' => 'symfony/symfony',
                ],
                'empty_data' => '',
            ))

            ->add('second', TextType::class, array(
                'label' => 'Git repo #2',
                'required' => true,
                'attr' => [
                    'placeholder' => 'laravel/laravel',
                ],
                'empty_data' => '',
            ))

            ->add('submit', SubmitType::class, array(
                'label' => 'Compare'
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'attr' => array('novalidate'=>'novalidate', 'class' => 'form-horizontal'),
//            'isFiltred' => false,
        ));
    }

//    public function getBlockPrefix()
//    {
//        return ''; // return an empty string here
//    }

}
