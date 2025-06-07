<?php

namespace App\Form;

use App\Entity\EmergencyAlert;
use App\Enum\AlertLevelEnum;
use App\Enum\EmergencyAlertStatusEnum;
use App\Enum\ThreatTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form type for creating and editing emergency alerts.
 *
 * This form provides fields for all properties of an EmergencyAlert entity,
 * including title, description, alert level, geographic coordinates,
 * radius, threat type, and status.
 */
class EmergencyAlertForm extends AbstractType
{
    /**
     * Builds the form structure with all necessary fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł',
                'attr' => [
                    'placeholder' => 'Wprowadź tytuł alertu'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Tytuł jest wymagany'
                    ]),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => 'Tytuł musi mieć co najmniej {{ limit }} znaków',
                        'maxMessage' => 'Tytuł nie może mieć więcej niż {{ limit }} znaków'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'attr' => [
                    'placeholder' => 'Szczegółowy opis sytuacji awaryjnej',
                    'rows' => 5
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Opis jest wymagany'
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'Opis musi mieć co najmniej {{ limit }} znaków'
                    ])
                ]
            ])
            ->add('alertLevel', ChoiceType::class, [
                'label' => 'Poziom zagrożenia',
                'choices' => AlertLevelEnum::choices(),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Poziom zagrożenia jest wymagany'
                    ])
                ]
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Szer. geograficzna',
                'scale' => 6,
                'attr' => [
                    'placeholder' => 'np. 52.229676'
                ],
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Szerokość geograficzna jest wymagana'
                    ]),
                    new Assert\Range([
                        'min' => -90,
                        'max' => 90,
                        'notInRangeMessage' => 'Szerokość geograficzna musi być między {{ min }} a {{ max }}'
                    ])
                ]
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Dł. geograficzna',
                'scale' => 6,
                'attr' => [
                    'placeholder' => 'np. 21.012229'
                ],
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Długość geograficzna jest wymagana'
                    ]),
                    new Assert\Range([
                        'min' => -180,
                        'max' => 180,
                        'notInRangeMessage' => 'Długość geograficzna musi być między {{ min }} a {{ max }}'
                    ])
                ]
            ])
            ->add('radiusKm', NumberType::class, [
                'label' => 'Promień w kilometrach',
                'scale' => 2,
                'attr' => [
                    'placeholder' => 'np. 5.0'
                ],
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Promień jest wymagany'
                    ]),
                    new Assert\Positive([
                        'message' => 'Promień musi być liczbą dodatnią'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 1000,
                        'message' => 'Promień nie może być większy niż {{ compared_value }} km'
                    ])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Rodzaj zagrożenia',
                'choices' => ThreatTypeEnum::choices(),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Rodzaj zagrożenia jest wymagany'
                    ])
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => EmergencyAlertStatusEnum::choices(),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Status jest wymagany'
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
            'data_class' => EmergencyAlert::class, // Binds the form to the EmergencyAlert entity
        ]);
    }
}
