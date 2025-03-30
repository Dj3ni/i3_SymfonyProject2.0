<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventOccurrence;
use DateTimeInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventOccurrenceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateEnd', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add("save", SubmitType::class, [
                "label"=> "Update"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventOccurrence::class,
        ]);
    }
}
