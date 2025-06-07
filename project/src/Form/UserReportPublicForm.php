<?php

namespace App\Form;

use App\Entity\UserReport;
use App\Enum\ThreatTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Public-facing form type for users to report threats or emergencies.
 *
 * This form provides a simplified interface with helpful guidance for users
 * to report emergency situations, including location, threat type, and description.
 * Unlike the admin version, it doesn't include status management.
 */
class UserReportPublicForm extends AbstractType
{
    /**
     * Builds the public-facing form structure with user-friendly guidance.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', TextareaType::class, [
                'label' => 'Lokalizacja',
                'help' => 'Podaj dokładną lokalizację zagrożenia',
                'attr' => [
                    'placeholder' => 'np. Warszawa, ul. Marszałkowska 1',
                    'rows' => 3
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
                'label' => 'Typ zagrożenia',
                'choices' => ThreatTypeEnum::choices(),
                'help' => 'Wybierz typ zagrożenia',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Rodzaj zgłoszenia jest wymagany'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'help' => 'Opisz szczegółowo zaobserwowane zagrożenie',
                'attr' => [
                    'placeholder' => 'Opisz co widzisz, jak wygląda sytuacja, czy są osoby poszkodowane, itp.',
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
