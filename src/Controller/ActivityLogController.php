<?php

namespace App\Controller;

use App\Repository\ActivityLogRepository;
use App\Service\ActivityLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ActivityLogController extends AbstractController
{
    public function __construct(
        private ActivityLogService $activityLogService,
        private ActivityLogRepository $activityLogRepository
    ) {}

    #[Route('/activitylog', name: 'activitylog', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $filters = [];

        if ($request->query->get('action')) {
            $filters['action'] = $request->query->get('action');
        }
        if ($request->query->get('entity_type')) {
            $filters['entityType'] = $request->query->get('entity_type');
        }

        $logs = $this->activityLogRepository->findBy(
            $filters,
            ['timestamp' => 'DESC']
        );

        return $this->render('activitylog.html.twig', [
            'logs' => $logs,
        ]);
    }

}