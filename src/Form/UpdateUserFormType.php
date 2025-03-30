<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UpdateUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
        ->add("firstName", TextType::class, [
            "attr" => [ 
                "placeholder" =>"Your Firstname",
                ]
        ])
        ->add("lastName", TextType::class, [
            "attr" => [ 
                "placeholder" =>"Your Last Name",
                ]
        ])
        ->add('phoneNumber', TextType::class, [
            "attr" => [ 
                "placeholder" =>"Your Phone Number",
                ]
        ])
        ->add('email', EmailType::class, [
            "attr" => [ 
                "placeholder" =>"Your email",
                "pattern" => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
            ],
            // 'constraints' => [
            //     new Email([
            //     'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
            //     'mode' => 'strict' // Optionnel : stricte vérification (recommandée)
            //     ])
            // ]
        ])
        ->add("avatarFile", FileType::class,[
            /* if avatarFile not in Entity these fields are compulsory*/
            "mapped" => false,
            
            "required"=> false,
            "constraints"=> [
                new Image(),
            ],
            
            "attr" => [
                "placeholder" => "Download your Avatar",
            ]
        ])
        
        // ->add('address', EntityType::class, [
        //     'class' => Address::class,
        //     'choice_label' => 'id',
        // ])
        ->add("save", SubmitType::class,['label' => 'Update'])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
