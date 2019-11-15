<?php

namespace App\Form;

use App\Entity\Merch\Product;
use App\Entity\Merch\ProductType as Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productName')
            ->add('productPrice')
            ->add('productInventory')
            ->add('productDescription')
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => false
            ])
            ->add('isOrderable')
            ->add('productType', EntityType::class,
                [
                    'class' => Type::class,
                    'choice_label' => 'productTypeName',
                    'multiple' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
