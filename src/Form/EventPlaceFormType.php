<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventPlace;
use App\Entity\GamingPlace;
use App\Form\GamingPlaceFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventPlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            // ->add("gamingPlace", GamingPlaceFormType::class)

            ->add("gamingPlaceChoice", ChoiceType::class,[
                "choices"=>[
                    "Choose an existing gaming place" =>"existing",
                    "Create a new gaming place"=>"new",
                ],
                "expanded" =>true, // radio buttons
                "multiple"=> false,
                // "label"=>"Choose a gaming place",
                "required"=>true,
                "mapped"=>false, // is not in entity so don't need to map it
                "data"=>"existing", //sets a default choice
                'attr' => ['class' => 'gaming-place-choice'],

            ])
            ->add('gamingPlace', EntityType::class, [
                'class' => GamingPlace::class,
                'choice_label' => 'name',
                'placeholder'=> "Choose a gaming place",
                "required"=>false,
                'attr' => [
                    'class' => 'gaming-place-existing'
                ], 
            ])
            ->add("newGamingPlace", GamingPlaceFormType::class,[
                "required"=> false,
                "mapped"=>false,
                'attr' => ['class' => 'gaming-place-new'], 
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventPlace::class,
        ]);
    }
}