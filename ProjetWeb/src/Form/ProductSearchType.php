<?php

namespace App\Form;

use App\Entity\Merch\ProductSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix Maximal'
                ]
            ])
            ->add('inStock', CheckboxType::class,[
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'En Stock'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
