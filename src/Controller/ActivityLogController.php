<?php

namespace App\Controller;

use App\Entity\ActivityLog;
use App\Repository\ActivityLogRepository;
use App\Service\ActivityLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/activitylog')]
#[IsGranted('ROLE_USER')]
class ActivityLogController extends AbstractController
{
    public function __construct(
        private ActivityLogService $activityLogService,
        private ActivityLogRepository $activityLogRepository
    ) {}

    #[Route('/', name: 'activity_log_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        try {
            $page = $request->query->getInt('page', 1);
            $limit = $request->query->getInt('limit', 10);
            $limit = min(max($limit, 1), 100); // Keep between 1-100

            $filters = [
                'action' => $request->query->get('action'),
                'entity_type' => $request->query->get('entity_type'),
                'user_id' => $request->query->getInt('user_id'),
                'start_date' => $request->query->get('start_date'),
                'end_date' => $request->query->get('end_date'),
            ];

            $paginator = $this->activityLogRepository->findPaginated(
                $page,
                $limit,
                $filters
            );

            $logs = [];
            foreach ($paginator->getIterator() as $log) {
                $logs[] = $this->formatLogEntry($log);
            }

            return new JsonResponse([
                'data' => $logs,
                'meta' => [
                    'total_items' => $paginator->count(),
                    'current_page' => $page,
                    'items_per_page' => $limit,
                    'total_pages' => ceil($paginator->count() / $limit),
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Failed to retrieve activity logs: ' . $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function formatLogEntry(ActivityLog $log): array
    {
        return [
            'id' => $log->getId(),
            'action' => $log->getAction(),
            'timestamp' => $log->getTimestamp()->format(\DateTimeInterface::ATOM),
            'entity' => [
                'type' => $log->getEntityType(),
            ],
            'details' => $log->getDetails(),
            'user' => $log->getUser() ? [
                'id' => $log->getUser()->getId(),
                'email' => $log->getUser()->getEmail(),
            ] : null,
        ];
    }
}