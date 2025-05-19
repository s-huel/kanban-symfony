<?php

namespace App\Controller;

use App\Repository\LaneRepository;
use App\Repository\TagRepository;
use App\Repository\TaskRepository;
use App\Repository\PriorityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    // Displays the Kanban board with lanes and tasks
    #[Route('/kanban', name: 'kanban')]
    public function index(
        LaneRepository     $laneRepository,
        TaskRepository     $taskRepository,
        PriorityRepository $priorityRepository,
        TagRepository      $tagRepository  // ADD THIS
    ): Response
    {
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

        // **Fetch all tags explicitly here**
        $allTags = $tagRepository->findAll();

        return $this->render('kanban.html.twig', [
            'lanes' => $lanes,
            'tasks' => $tasks,
            'priorities' => $priorityArray,
            'allTags' => $allTags,  // <-- pass all tags for the frontend
        ]);
    }
}