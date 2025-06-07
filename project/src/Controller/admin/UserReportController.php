<?php

namespace App\Controller\admin;

use App\Entity\UserReport;
use App\Form\UserReportForm;
use App\Repository\UserReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user_report')]
final class UserReportController extends AbstractController
{
    #[Route(name: 'app_user_report_index', methods: ['GET'])]
    /**
     * Wyświetla listę wszystkich zgłoszeń użytkowników.
     * Obsługuje wyszukiwanie zgłoszeń.
     */
    public function index(Request $request, UserReportRepository $userReportRepository): Response
    {
        $searchTerm = $request->query->get('search');

        return $this->render('admin/user_report/index.html.twig', [
            'user_reports' => $userReportRepository->findBySearchTerm($searchTerm),
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_user_report_new', methods: ['GET', 'POST'])]
    /**
     * Tworzy nowe zgłoszenie użytkownika.
     * Obsługuje formularz tworzenia zgłoszenia i zapisuje je w bazie danych.
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userReport = new UserReport();
        $form = $this->createForm(UserReportForm::class, $userReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userReport);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user_report/new.html.twig', [
            'user_report' => $userReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_report_show', methods: ['GET'])]
    /**
     * Wyświetla szczegóły wybranego zgłoszenia użytkownika.
     */
    public function show(UserReport $userReport): Response
    {
        return $this->render('admin/user_report/show.html.twig', [
            'user_report' => $userReport,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_report_edit', methods: ['GET', 'POST'])]
    /**
     * Edytuje istniejące zgłoszenie użytkownika.
     * Obsługuje formularz edycji i aktualizuje dane w bazie.
     */
    public function edit(Request $request, UserReport $userReport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserReportForm::class, $userReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user_report/edit.html.twig', [
            'user_report' => $userReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_report_delete', methods: ['POST'])]
    /**
     * Usuwa zgłoszenie użytkownika z systemu.
     * Weryfikuje token CSRF przed wykonaniem operacji.
     */
    public function delete(Request $request, UserReport $userReport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userReport->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($userReport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_report_index', [], Response::HTTP_SEE_OTHER);
    }
}
