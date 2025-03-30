<?php

namespace App\Form;

use App\Entity\Event;
use App\Enum\EventType;
use App\Enum\RecurrenceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "attr" =>[
                    "placeholder" => "Awesome Event Title"
                ]
            ])
            ->add('dateStart', null, [
                'widget' => 'single_text',
            ])
            ->add('dateEnd', null, [
                'widget' => 'single_text',
            ])
            ->add('recurrenceType', EnumType::class, [
                "class"=>RecurrenceType::class,
            ])
            ->add('recurrenceEnd', null, [
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, [
                "attr" => [
                    "placeholder" => "Describe your event here",
                    
                ]
            ])
            ->add('fee', MoneyType::class)
            ->add('eventType', EnumType::class, [
                "class"=>EventType::class,
            ])
                // Add Gaming Place
        
            // ->add("eventPlaces", CollectionType::class, [
            //     "entry_type"=> EventPlaceFormType::class,
            //     "allow_add"=>true,//I want to add multiple eventPlaces
            //     "by_reference"=>false, //I only want to add or remove
            //     "entry_options"=>[
            //         "label"=>false, // I don't want the id to appear
            //     ],
            // ])    

            ->add("save", SubmitType::class, [
                "label"=> "Save this event"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
