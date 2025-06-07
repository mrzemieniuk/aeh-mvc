<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\Recaptcha;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    /**
     * Obsługuje proces rejestracji nowego użytkownika.
     * Weryfikuje formularz, sprawdza reCAPTCHA i tworzy nowe konto użytkownika.
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, Recaptcha $recaptcha): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Formularz zawiera błędy. Sprawdź poniższe pola i spróbuj ponownie.');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $recaptchaToken = $request->get('g-recaptcha-response');

            $recaptchaValid = $recaptcha->validateRecaptcha($recaptchaToken, $this->getParameter('recaptcha_secret_key'));

            if (!$recaptchaValid) {
                $this->addFlash('danger', 'Weryfikacja reCAPTCHA nie powiodła się. Spróbuj ponownie.');
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form,
                    'recaptcha_site_key' => $this->getParameter('recaptcha_site_key'),
                ]);
            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Rejestracja zakończona pomyślnie. Możesz się teraz zalogować.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'recaptcha_site_key' => $this->getParameter('recaptcha_site_key'),
        ]);
    }
}
