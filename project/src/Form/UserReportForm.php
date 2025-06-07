<?php

namespace App\Form;

use App\Entity\UserReport;
use App\Enum\ThreatTypeEnum;
use App\Enum\UserReportStatusEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form type for creating and editing user reports of threats or emergencies.
 *
 * This form provides fields for reporting emergency situations,
 * including location, threat type, description, and status.
 */
class UserReportForm extends AbstractType
{
    /**
     * Builds the form structure with all necessary fields for user reports.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', TextType::class, [
                'label' => 'Lokalizacja',
                'attr' => [
                    'placeholder' => 'Podaj dokładną lokalizację zdarzenia'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Lokalizacja jest wymagana'
                    ]),
                    new Assert\Length([
                        'min' => 5,
                        'minMessage' => 'Lokalizacja musi mieć co najmniej {{ limit }} znaków'
                    ])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Rodzaj zagrożenia',
                'choices' => ThreatTypeEnum::choices(),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Rodzaj zgłoszenia jest wymagany'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'attr' => [
                    'placeholder' => 'Opisz szczegółowo sytuację',
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
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => UserReportStatusEnum::choices(),
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
            'data_class' => UserReport::class, // Binds the form to the UserReport entity
        ]);
    }
}
