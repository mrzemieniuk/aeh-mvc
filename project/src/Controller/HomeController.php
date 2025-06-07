<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\ThreatTypeEnum;
use App\Repository\EmergencyAlertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
    /**
     * Wyświetla stronę główną z alertami i mapą.
     * Obsługuje filtrowanie alertów według typu i lokalizacji.
     */
    public function index(Request $request, EmergencyAlertRepository $emergencyAlertRepository): Response
    {
        $type = $request->query->get('type');
        $location = $request->query->get('location');

        $emergencyAlerts = $emergencyAlertRepository->findPublicFiltered($type, $location);

        $allAlerts = $emergencyAlertRepository->findPublic();
        $locations = [];
        foreach ($allAlerts as $alert) {
            $locationKey = $alert->getLatitude() . ',' . $alert->getLongitude();
            $locations[$locationKey] = sprintf('%.6f, %.6f', $alert->getLatitude(), $alert->getLongitude());
        }

        $coordinates = array_map(fn($location) => [
            'lat' => $location->getLatitude(),
            'lng' => $location->getLongitude(),
        ], $emergencyAlerts);

        return $this->render('home.html.twig', [
            'last_username' => '',
            'error' => null,
            'emergency_alerts' => $emergencyAlerts,
            'coordinates' => $coordinates,
            'google_maps_api_key' => $this->getParameter('google_maps_api_key'),
            'threat_types' => ThreatTypeEnum::choices(),
            'locations' => $locations,
            'selected_type' => $type,
            'selected_location' => $location,
        ]);
    }
}
