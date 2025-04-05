<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Lane;
use App\Repository\TaskRepository;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// All task routes are under /api/task and requires to be a user
#[Route('/api/task')]
#[IsGranted('ROLE_USER')]
class TaskController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TaskRepository $taskRepository
    ) {}

    // Creates a new task and assigns it to a lane
    #[Route('/create', name: 'task_create', methods: ['POST'])]
    public function createTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Basic input validation
        if (!$data || !isset($data['title'], $data['lane_id'])) {
            return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Fetch the target lane
        $lane = $this->entityManager->getRepository(Lane::class)->find($data['lane_id']);
        if (!$lane) {
            return new JsonResponse(['error' => 'Lane not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Create + save new task
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setLane($lane);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Task created successfully'], JsonResponse::HTTP_CREATED);
    }

    // Updates an existing task (title or lane)
    #[Route('/update/{id}', name: 'task_update', methods: ['PUT'])]
    public function updateTask(int $id, Request $request, EntityManagerInterface $entityManager, ActivityLogService $activityLogService): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Track changes for activity log
        $oldTitle = $task->getTitle();
        $oldLaneId = $task->getLane() ? $task->getLane()->getId() : null;

        // Update task title if provided
        if (isset($data['title'])) {
            $task->setTitle($data['title']);
        }

        // Update lane if provided
        if (isset($data['lane_id'])) {
            $lane = $this->entityManager->getRepository(Lane::class)->find($data['lane_id']);
            if (!$lane) {
                return new JsonResponse(['error' => 'Lane not found'], JsonResponse::HTTP_NOT_FOUND);
            }
            $task->setLane($lane);
        }

        $this->entityManager->flush();

        // Detect changes and log them
        $changes = [];
        if (isset($data['title']) && $oldTitle !== $task->getTitle()) {
            $changes['title'] = [$oldTitle, $task->getTitle()];
        }

        if (isset($data['lane_id']) && $oldLaneId !== ($task->getLane() ? $task->getLane()->getId() : null)) {
            $changes['lane'] = [$oldLaneId, $task->getLane() ? $task->getLane()->getId() : null];
        }

        if (!empty($changes)) {
            $activityLogService->logActivity('Updated Task', $task, $changes);
        }

        return new JsonResponse(['message' => 'Task updated successfully']);
    }

    // Deletes a task
    #[Route('/delete/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function deleteTask(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Task deleted successfully']);
    }
}
