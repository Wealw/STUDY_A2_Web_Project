<?php

namespace App\Form;

use App\Security\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_mail')
            ->add('user_first_name')
            ->add('user_last_name')
            ->add('user_phone')
            ->add('user_postal_code')
            ->add('user_address')
            ->add('user_city')
            ->add('user_password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('centerId', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Centre CESI',
                'choices' => [
                    'Strasbourg' => 1,
                    'Nanterre' => 2,
                    'Nancy' => 3,
                    'Reims' => 4,
                    'Toulouse' => 5
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms'
        ]);
    }
}
