<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Form type for user registration.
 *
 * This form collects all necessary information to create a new user account,
 * including personal details (name, surname, phone, email), password with confirmation,
 * and terms agreement. It includes validation constraints to ensure data quality.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Builds the registration form with all required fields and validation constraints.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Imię',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wprowadź imię'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać imię',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Imię powinno mieć co najmniej {{ limit }} znaków',
                        'maxMessage' => 'Imię nie może mieć więcej niż {{ limit }} znaków',
                    ]),
                ],
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nazwisko',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wprowadź nazwisko'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać nazwisko',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Nazwisko powinno mieć co najmniej {{ limit }} znaków',
                        'maxMessage' => 'Nazwisko nie może mieć więcej niż {{ limit }} znaków',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefon',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wprowadź numer telefonu'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać numer telefonu',
                    ]),
                    new Length([
                        'min' => 9,
                        'max' => 20,
                        'minMessage' => 'Numer telefonu powinien mieć co najmniej {{ limit }} znaków',
                        'maxMessage' => 'Numer telefonu nie może mieć więcej niż {{ limit }} znaków',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wprowadź adres email (opcjonalnie)'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Hasło',
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'new-password',
                    ],
                ],
                'second_options' => [
                    'label' => 'Powtórz hasło',
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'new-password',
                    ],
                ],
                'invalid_message' => 'Hasła muszą być identyczne.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę wprowadzić hasło',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twoje hasło powinno mieć co najmniej {{ limit }} znaków',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Akceptuję regulamin',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Musisz zaakceptować regulamin.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
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
