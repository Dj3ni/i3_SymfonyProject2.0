<?php

namespace App\Form;

use Assert\Email;
use App\Entity\User;
use Webmozart\Assert\Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    "placeholder" =>"Your password",
                    ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add("save", SubmitType::class,['label' => 'Register'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
