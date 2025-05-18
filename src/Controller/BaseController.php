<?php

namespace App\Controller;

use App\Repository\LaneRepository;
use App\Repository\TaskRepository;
use App\Repository\PriorityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    // Displays the Kanban board with lanes and tasks
    #[Route('/kanban', name: 'kanban')]
    public function index(LaneRepository $laneRepository, TaskRepository $taskRepository, PriorityRepository $priorityRepository): Response
    {
        // Fetch all lanes and tasks from the repositories
        $lanes = $laneRepository->findAll();
        $tasks = $taskRepository->findAll();
        $priorities = $priorityRepository->findAll();

        $priorityArray = [];
        foreach ($priorities as $priority) {
            $priorityArray[$priority->getId()] = [
                'title' => $priority->getTitle(),
                'color' => $priority->getColor(),
            ];
        }

        // Render the kanban.html.twig template with lanes and tasks data
        return $this->render('kanban.html.twig', [
            'lanes' => $lanes,
            'tasks' => $tasks,
            'priorities' => $priorityArray,
        ]);
    }
}
