<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class,[
                "required" =>false,
                "empty_data" => "",
                "attr"=>[
                    "placeholder" => "Your name"
                ],
                'constraints' => [
                    new Assert\Length([
                        "min" => 3,
                        "minMessage" => 'Le nom doit contenir au moins {{ limit }} caractère '
                    ])
                ],
            ])
            ->add("email", EmailType::class,[
                "required" => true,
                "empty_data" => "",
                "attr"=>[
                    "placeholder" => "Your email",
                    "pattern" => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
                ],
                // 'constraints' => [
                //     new Assert\Regex([
                //         "pattern" => "^[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]$",
                //         'message' => 'Veuillez entrer un email valide (ex: nom@example.com).'
                //     ])
                // ],                
            ])
            ->add("message", TextareaType::class,[
                "required" => true,
                "empty_data" => "",
                "attr"=>[
                    "placeholder" => "Write your message here"
                ],
                // 'constraints' => NotBlank::CLASS_CONSTRAINT,
            ] )
            ->add("save", SubmitType::class, [
                'label' => 'Contact us'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
