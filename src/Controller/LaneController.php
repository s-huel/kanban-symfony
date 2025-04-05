<?php

namespace App\Controller;

use App\DTO\UpdateLaneRequestDTO;
use App\Entity\Lane;
use App\Repository\LaneRepository;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// All routes here are prefixed with /api/lane and protected by user
#[Route('/api/lane')]
#[IsGranted('ROLE_USER')]
class LaneController extends AbstractController
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    // Fetch and return all lanes
    #[Route('/', name: 'lane_list', methods: ['GET'])]
    public function index(LaneRepository $laneRepository): JsonResponse
    {
        $lanes = $laneRepository->findAll();

        // Convert lanes to plain array format
        $data = [];
        foreach ($lanes as $lane) {
            $data[] = [
                'id' => $lane->getId(),
                'title' => $lane->getTitle(),
            ];
        }

        return $this->json($data);
    }

    // Create a new lane
    #[Route('/create', name: 'lane_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validate the input data
        if (!$data || !isset($data['title'])) {
            return new JsonResponse(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        // Create + persist the new lane
        $lane = new Lane();
        $lane->setTitle($data['title']);

        $entityManager->persist($lane);
        $entityManager->flush();

        // Log the activity to the activity logger
        $this->activityLogService->logActivity('Lane Added', $lane);

        return new JsonResponse([
            'id' => $lane->getId(),
            'title' => $lane->getTitle(),
        ], Response::HTTP_CREATED);
    }

    // Update an existing lane title
    #[Route('/update/{id}', name: 'lane_update', methods: ['PUT'])]
    public function update(
        Lane $lane,
        #[MapRequestPayload] UpdateLaneRequestDTO $updateLaneRequestDTO,
        EntityManagerInterface $entityManager,
        ActivityLogService $activityLogService)
    {
        $oldTitle = $lane->getTitle();

        // Update title from DTO
        $lane->setTitle($updateLaneRequestDTO->title);

        try {
            $entityManager->flush();

            // Log the update with old and new title
            $this->activityLogService->logActivity('Lane Updated', $lane, [
                'title' => [$oldTitle, $lane->getTitle()]
            ]);

            return new JsonResponse([
                'id' => $lane->getId(),
                'title' => $lane->getTitle(),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to update lane: '.$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete a lane
    #[Route('/delete/{id}', name: 'lane_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $lane = $entityManager->getRepository(Lane::class)->find($id);

        if (!$lane) {
            return new JsonResponse(['error' => 'Lane not found'], Response::HTTP_NOT_FOUND);
        }

        // Log the deletion activity
        $this->activityLogService->logActivity('Lane Deleted', $lane);

        $entityManager->remove($lane);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Lane deleted successfully'], Response::HTTP_OK);
    }
}
