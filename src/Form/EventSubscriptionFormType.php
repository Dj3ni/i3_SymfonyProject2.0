<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventSubscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EventSubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // on envoie un array de EventOccurrenceSubscription depuis le controller
            // Symfony associera la clé de l'array envoyé (eventOccurrenceSubscriptions)
            // Attention à ce nom car il doit être le même que la clé des données fournies depuis le controller
            ->add('eventOccurrenceSubscriptions', CollectionType::class, [
                'entry_type' => EventOccurrenceSubscriptionType::class,
                'entry_options' => [
                    'label' => false,  // Disable labels for each form
                ],
                'allow_add' => true,
                'allow_delete' => true, 
                'by_reference' => false,
                // ATTENTION AU NOM! eventOccurrenceSubscriptions doit correspondre au nom de la clé de l'array envoyé
                'data' => $options['data']['eventOccurrenceSubscriptions'] ?? [], 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
