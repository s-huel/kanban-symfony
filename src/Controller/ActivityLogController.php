<?php

namespace App\Controller;

use App\Entity\ActivityLog;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/activity-logs')]
#[IsGranted('ROLE_USER')]
class ActivityLogController extends AbstractController
{
    private ActivityLogService $activityLogService;
    private EntityManagerInterface $entityManager;

    public function __construct(ActivityLogService $activityLogService, EntityManagerInterface $entityManager)
    {
        $this->activityLogService = $activityLogService;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'activity_log', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $activityLogs = $this->entityManager->getRepository(ActivityLog::class)->findAll();

        $logs = [];
        foreach ($activityLogs as $log) {
            $logs[] = [
                'action' => $log->getAction(),
                'timestamp' => $log->getTimestamp()->format('Y-m-d H:i:s'),
                'entityType' => $log->getEntityType(),
                'details' => $log->getDetails(),
                'user' => $log->getUser() ? $log->getUser()->getUsername() : 'Unknown',
            ];
        }

        return new JsonResponse($logs);
    }
}
