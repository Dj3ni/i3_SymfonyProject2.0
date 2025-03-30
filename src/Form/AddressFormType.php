<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\GamingPlace;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('locality', TextType::class,[
                "required"=> false,
                "attr"=>[
                    "placeholder"=> "Locality"
                ]
            ])
            ->add('street', TextType::class,[
                "attr"=>[
                    "placeholder"=> "Street"
                ]
            ])
            ->add('number', TextType::class,[
                "attr"=>[
                    "placeholder"=> "Number"
                ]
            ])
            ->add('postCode', IntegerType::class,[
                "attr"=>[
                    "max-length"=>6,
                    "placeholder"=> "Post Code"
                ],
            ])
            ->add('city', TextType::class,[
                "attr"=>[
                    "max-length"=>6,
                    "placeholder"=> "City"
                ],
            ])
            ->add('country', TextType::class,[
                "data"=> "Belgium",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
