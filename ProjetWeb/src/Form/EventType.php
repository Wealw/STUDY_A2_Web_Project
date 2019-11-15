<?php

namespace App\Form;

use App\Entity\Social\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{DateTimeType, DateType, FileType, NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Social\EventType as Category;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event_name')
            ->add('event_description')
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('event_location')
            ->add('event_price', NumberType::class)
            ->add('event_date', DateTimeType::class)
            ->add('event_type', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'eventTypeName'
            ])
            //->add('event_period')
            //->add('event_type', ChoiceType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'translation_domain' => 'forms'
        ]);
    }
}
