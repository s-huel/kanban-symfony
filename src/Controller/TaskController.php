<?php

namespace App\Controller;

use App\DTO\UpdateTaskRequestDTO;
use App\Entity\Task;
use App\Repository\TaskRepository;
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

#[Route('/api/task')]
#[IsGranted('ROLE_USER')]
class TaskController extends AbstractController
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    #[Route('/', name: 'task_list', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): JsonResponse
    {
        $tasks = $taskRepository->findAll();

        $data = [];
        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'lane_id' => $task->getLane()->getId(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/create', name: 'task_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, LaneRepository $laneRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['title']) || !isset($data['lane_id'])) {
            return new JsonResponse(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        $lane = $laneRepository->find($data['lane_id']);
        if (!$lane) {
            return new JsonResponse(['error' => 'Lane not found'], Response::HTTP_BAD_REQUEST);
        }

        $task = new Task();
        $task->setTitle($data['title']);
        $task->setLane($lane);

        $entityManager->persist($task);
        $entityManager->flush();

        $this->activityLogService->logActivity('Task Added', $task);

        return new JsonResponse([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'lane_id' => $task->getLane()->getId(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(
        Task $task,
        #[MapRequestPayload] UpdateTaskRequestDTO $updateTaskRequestDTO,
        EntityManagerInterface $entityManager,
        LaneRepository $laneRepository,
    ): JsonResponse {
        $oldTitle = $task->getTitle();
        $oldLaneId = $task->getLane()->getId();

        $task->setTitle($updateTaskRequestDTO->title);

        $lane = $laneRepository->find($updateTaskRequestDTO->lane_id);
        if (!$lane) {
            return new JsonResponse(['error' => 'Lane not found'], Response::HTTP_BAD_REQUEST);
        }
        $task->setLane($lane);

        try {
            $entityManager->flush();

            $this->activityLogService->logActivity('Task Updated', $task, [
                'title' => [$oldTitle, $task->getTitle()],
                'lane_id' => [$oldLaneId, $task->getLane()->getId()]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to update task: '.$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'lane_id' => $task->getLane()->getId(),
        ], Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Lane not found'], Response::HTTP_NOT_FOUND);
        }

        $this->activityLogService->logActivity('Task Deleted', $task, [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'lane_id' => $task->getLane()->getId(),
        ]);

        $this->activityLogService->logActivity('Task Deleted', $task);


        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Lane deleted successfully'], Response::HTTP_OK);
    }
}
