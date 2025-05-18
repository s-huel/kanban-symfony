<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Lane;
use App\Entity\Priority;
use App\Repository\TaskRepository;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/task')]
class TaskController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TaskRepository $taskRepository
    ) {}

    // Create a new task
    #[Route('/create', name: 'task_create', methods: ['POST'])]
    public function createTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validate required fields
        if (!$data || empty($data['title']) || empty($data['lane_id']) || empty($data['priority_id'])) {
            return new JsonResponse(['error' => 'Invalid input. Title, lane_id, and priority_id are required.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Find associated lane
        $lane = $this->entityManager->getRepository(Lane::class)->find($data['lane_id']);
        if (!$lane) {
            return new JsonResponse(['error' => 'Lane not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Find associated priority
        $priority = $this->entityManager->getRepository(Priority::class)->find($data['priority_id']);
        if (!$priority) {
            return new JsonResponse(['error' => 'Priority not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Create and persist the task
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setLane($lane);
        $task->setPriority($priority);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Task created successfully',
            'task' => [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'lane_id' => $lane->getId(),
                'priority_id' => $priority->getId(),
            ]
        ], JsonResponse::HTTP_CREATED);
    }


    // Update an existing task
    #[Route('/update/{id}', name: 'task_update', methods: ['PUT'])]
    public function updateTask(int $id, Request $request, ActivityLogService $activityLogService): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Update fields
        if (isset($data['title'])) {
            $task->setTitle($data['title']);
        }
        if (isset($data['lane_id'])) {
            $lane = $this->entityManager->getRepository(Lane::class)->find($data['lane_id']);
            if (!$lane) {
                return new JsonResponse(['error' => 'Lane not found'], JsonResponse::HTTP_NOT_FOUND);
            }
            $task->setLane($lane);
        }

        if (isset($data['priority_id'])) {
            $priority = $this->entityManager->getRepository(Priority::class)->find($data['priority_id']);
            if (!$priority) {
                return new JsonResponse(['error' => 'Priority not found'], JsonResponse::HTTP_NOT_FOUND);
            }
            $task->setPriority($priority);
        }

        $this->entityManager->flush();

        // Log the updates
        $activityLogService->logActivity('Updated Task', $task);

        return new JsonResponse(['message' => 'Task updated successfully']);
    }

    // Add a priority to a task that currently has none
    #[Route('/{id}/add-priority', name: 'task_add_priority', methods: ['POST'])]
    public function addPriority(int $id, Request $request): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (empty($data['priority_id'])) {
            return new JsonResponse(['error' => 'priority_id is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $priority = $this->entityManager->getRepository(Priority::class)->find($data['priority_id']);
        if (!$priority) {
            return new JsonResponse(['error' => 'Priority not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Only add priority if task doesn't already have one
        if ($task->getPriority()) {
            return new JsonResponse(['error' => 'Task already has a priority'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $task->setPriority($priority);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Priority added to task successfully']);
    }

    // Update the priority of an existing task
    #[Route('/{id}/update-priority', name: 'task_update_priority', methods: ['PUT'])]
    public function updatePriority(int $id, Request $request): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (empty($data['priority_id'])) {
            return new JsonResponse(['error' => 'priority_id is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $priority = $this->entityManager->getRepository(Priority::class)->find($data['priority_id']);
        if (!$priority) {
            return new JsonResponse(['error' => 'Priority not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $task->setPriority($priority);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Priority updated successfully']);
    }

    // Remove the priority from a task
    #[Route('/{id}/remove-priority', name: 'task_remove_priority', methods: ['DELETE'])]
    public function removePriority(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (!$task->getPriority()) {
            return new JsonResponse(['error' => 'Task has no priority to remove'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $task->setPriority(null);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Priority removed from task successfully']);
    }

    // Delete a task
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
