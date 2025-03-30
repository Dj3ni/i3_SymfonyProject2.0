<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\EventOccurrence;
use Symfony\Component\Form\AbstractType;
use App\Entity\EventOccurrenceSubscription;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EventOccurrenceSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             // Checkbox for selecting the occurrence
            ->add('subscribe', CheckboxType::class, [
                'mapped' => false, 
                'required' => false, // Allows the user to not select some occurrences
            ])
            // Number of participants for each occurrence
            ->add('numberParticipants', IntegerType::class, [
                'label' => 'Number of Participants',
                'required' => false, // Optional, but you can make it required
                'attr' => [
                    "min"=> 1,
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventOccurrenceSubscription::class,
        ]);
    }
}
