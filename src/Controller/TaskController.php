<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Lane;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/task')]
#[IsGranted('ROLE_USER')]
class TaskController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TaskRepository $taskRepository
    ) {}

    #[Route('/create', name: 'task_create', methods: ['POST'])]
    public function createTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['title'], $data['lane_id'])) {
            return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $lane = $this->entityManager->getRepository(Lane::class)->find($data['lane_id']);
        if (!$lane) {
            return new JsonResponse(['error' => 'Lane not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $task = new Task();
        $task->setTitle($data['title']);
        $task->setLane($lane);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Task created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'task_update', methods: ['PUT'])]
    public function updateTask(int $id, Request $request): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

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

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Task updated successfully']);
    }

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
