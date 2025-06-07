<?php

namespace App\Controller;

use App\Entity\UserReport;
use App\Enum\UserReportStatusEnum;
use App\Form\UserReportPublicForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserReportPublicController extends AbstractController
{
    #[Route('/report/my', name: 'app_user_report_my', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    /**
     * Wyświetla listę zgłoszeń utworzonych przez zalogowanego użytkownika.
     * Sortuje zgłoszenia od najnowszych do najstarszych.
     */
    public function myReports(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $userReports = $entityManager->getRepository(UserReport::class)->findBy(
            ['createdBy' => $user],
            ['createdAt' => 'DESC'] // Sort by creation date, newest first
        );

        return $this->render('user_report/my_reports.html.twig', [
            'user_reports' => $userReports,
        ]);
    }
    #[Route('/report/new', name: 'app_user_report_new_public', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    /**
     * Obsługuje tworzenie nowego zgłoszenia przez użytkownika.
     * Zapisuje zgłoszenie w bazie danych i wyświetla komunikat potwierdzający.
     */
    public function newReport(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userReport = new UserReport();
        $form = $this->createForm(UserReportPublicForm::class, $userReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Formularz zawiera błędy. Sprawdź poniższe pola i spróbuj ponownie.');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $userReport->setStatus(UserReportStatusEnum::PENDING->name);
            $userReport->setCreatedAt(new \DateTimeImmutable());
            $userReport->setCreatedBy($this->getUser());

            $entityManager->persist($userReport);
            $entityManager->flush();

            $this->addFlash('success', 'Twoje zgłoszenie zostało wysłane. Dziękujemy za pomoc w zapewnieniu bezpieczeństwa!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('user_report/new.html.twig', [
            'form' => $form,
        ]);
    }
}
