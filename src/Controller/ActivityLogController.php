<?php

namespace App\Controller;

use App\Repository\ActivityLogRepository;
use App\Service\ActivityLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')] // Only allow logged-in users to access this controller
class ActivityLogController extends AbstractController
{
    // Inject the service + repository via constructor for cleaner code
    public function __construct(
        private ActivityLogService $activityLogService,
        private ActivityLogRepository $activityLogRepository
    ) {}

    // Route that fetches and displays the activity logs
    #[Route('/activitylog', name: 'activitylog', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $filters = [];

        // Filter logs by specific action type (e.g., Task Added, Lane Deleted)
        if ($request->query->get('action')) {
            $filters['action'] = $request->query->get('action');
        }

        // Filter logs by the type of entity affected (e.g., Task, Lane)
        if ($request->query->get('entity_type')) {
            $filters['entityType'] = $request->query->get('entity_type');
        }

        // Retrieve filtered logs from the database, sorted by newest first
        $logs = $this->activityLogRepository->findBy(
            $filters,
            ['timestamp' => 'DESC']
        );

        // Render the logs into the activitylog.html.twig template
        return $this->render('activitylog.html.twig', [
            'logs' => $logs,
        ]);
    }

}