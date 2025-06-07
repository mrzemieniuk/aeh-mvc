<?php

namespace App\Controller\admin;

use App\Entity\EmergencyAlert;
use App\Form\EmergencyAlertForm;
use App\Repository\EmergencyAlertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/emergency_alert')]
final class EmergencyAlertController extends AbstractController
{
    #[Route(name: 'app_emergency_alert_index', methods: ['GET'])]
    /**
     * Wyświetla listę wszystkich alertów awaryjnych.
     * Obsługuje wyszukiwanie alertów.
     */
    public function index(Request $request, EmergencyAlertRepository $emergencyAlertRepository): Response
    {
        $searchTerm = $request->query->get('search');

        return $this->render('admin/emergency_alert/index.html.twig', [
            'emergency_alerts' => $emergencyAlertRepository->findBySearchTerm($searchTerm),
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_emergency_alert_new', methods: ['GET', 'POST'])]
    /**
     * Tworzy nowy alert.
     * Obsługuje formularz tworzenia alertu i zapisuje go w bazie danych.
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emergencyAlert = new EmergencyAlert();
        $form = $this->createForm(EmergencyAlertForm::class, $emergencyAlert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emergencyAlert);
            $entityManager->flush();

            return $this->redirectToRoute('app_emergency_alert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/emergency_alert/new.html.twig', [
            'emergency_alert' => $emergencyAlert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emergency_alert_show', methods: ['GET'])]
    /**
     * Wyświetla szczegóły wybranego alertu.
     */
    public function show(EmergencyAlert $emergencyAlert): Response
    {
        return $this->render('admin/emergency_alert/show.html.twig', [
            'emergency_alert' => $emergencyAlert,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emergency_alert_edit', methods: ['GET', 'POST'])]
    /**
     * Edytuje istniejący alert.
     * Obsługuje formularz edycji i aktualizuje dane w bazie.
     */
    public function edit(Request $request, EmergencyAlert $emergencyAlert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmergencyAlertForm::class, $emergencyAlert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emergency_alert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/emergency_alert/edit.html.twig', [
            'emergency_alert' => $emergencyAlert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emergency_alert_delete', methods: ['POST'])]
    /**
     * Usuwa alert.
     * Weryfikuje token CSRF przed wykonaniem operacji.
     */
    public function delete(Request $request, EmergencyAlert $emergencyAlert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emergencyAlert->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($emergencyAlert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emergency_alert_index', [], Response::HTTP_SEE_OTHER);
    }
}
