<?php

namespace App\Controller;

use App\Repository\LaneRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    #[Route('/kanban', name: 'kanban')]
    public function index(LaneRepository $laneRepository, TaskRepository $taskRepository): Response
    {
        $lanes = $laneRepository->findAll();
        $tasks = $taskRepository->findAll();

        return $this->render('kanban.html.twig', [
            'lanes' => $lanes,
            'tasks' => $tasks,
        ]);
    }
}
