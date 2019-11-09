<?php

namespace App\Form;

use App\Entity\Social\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{FileType, NumberType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event_name')
            ->add('event_description')
            ->add('event_image_path', FileType::class, [
                'label' => false
            ])
            ->add('event_location')
            ->add('event_price', NumberType::class)
            ->add('event_date')
            //->add('event_period')
            //->add('event_type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
