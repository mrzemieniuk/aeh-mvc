<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form type for creating and editing user profiles.
 *
 * This form provides fields for the basic user information,
 * including phone number, email, first name, and last name.
 */
class UserForm extends AbstractType
{
    /**
     * Builds the form structure with all necessary fields for user information.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', TextType::class, [
                'label' => 'Numer telefonu',
                'attr' => [
                    'placeholder' => 'np. +48123456789'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Numer telefonu jest wymagany'
                    ]),
                    new Assert\Length([
                        'min' => 9,
                        'max' => 20,
                        'minMessage' => 'Numer telefonu musi mieć co najmniej {{ limit }} znaków',
                        'maxMessage' => 'Numer telefonu nie może mieć więcej niż {{ limit }} znaków'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9]+$/',
                        'message' => 'Numer telefonu może zawierać tylko cyfry i opcjonalnie znak "+" na początku'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => [
                    'placeholder' => 'np. jan.kowalski@example.com'
                ],
                'constraints' => [
                    new Assert\Email([
                        'message' => 'Podany adres email {{ value }} jest nieprawidłowy'
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Imię',
                'attr' => [
                    'placeholder' => 'np. Jan'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Imię jest wymagane'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Imię musi mieć co najmniej {{ limit }} znaki',
                        'maxMessage' => 'Imię nie może mieć więcej niż {{ limit }} znaków'
                    ])
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nazwisko',
                'attr' => [
                    'placeholder' => 'np. Kowalski'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Nazwisko jest wymagane'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Nazwisko musi mieć co najmniej {{ limit }} znaki',
                        'maxMessage' => 'Nazwisko nie może mieć więcej niż {{ limit }} znaków'
                    ])
                ]
            ])
        ;
    }

    /**
     * Configures the options for this form.
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Binds the form to the User entity
        ]);
    }
}
