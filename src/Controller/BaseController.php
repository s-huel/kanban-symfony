<?php

namespace App\Controller;

use App\Repository\LaneRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    // Displays the Kanban board with lanes and tasks
    #[Route('/kanban', name: 'kanban')]
    public function index(LaneRepository $laneRepository, TaskRepository $taskRepository): Response
    {
        // Fetch all lanes and tasks from the repositories
        $lanes = $laneRepository->findAll();
        $tasks = $taskRepository->findAll();

        // Render the kanban.html.twig template with lanes and tasks data
        return $this->render('kanban.html.twig', [
            'lanes' => $lanes,
            'tasks' => $tasks,
        ]);
    }
}
