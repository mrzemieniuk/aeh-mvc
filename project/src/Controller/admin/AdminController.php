<?php

namespace App\Controller\admin;

use App\Repository\EmergencyAlertRepository;
use App\Repository\UserRepository;
use App\Repository\UserReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_home', methods: ['GET'])]
    /**
     * Wyświetla panel administracyjny ze statystykami.
     * Pokazuje liczby alertów, użytkowników i zgłoszeń w systemie.
     */
    public function index(
        EmergencyAlertRepository $emergencyAlertRepository,
        UserRepository $userRepository,
        UserReportRepository $userReportRepository
    ): Response
    {
        $alertsCount = $emergencyAlertRepository->count([]);
        $usersCount = $userRepository->count([]);
        $reportsCount = $userReportRepository->count([]);

        return $this->render('admin/index.html.twig', [
            'alerts_count' => $alertsCount,
            'users_count' => $usersCount,
            'reports_count' => $reportsCount,
        ]);
    }
}
