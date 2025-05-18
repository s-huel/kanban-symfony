<?php

namespace App\Controller;

use App\Entity\Priority;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PriorityController extends AbstractController
{
    // Create a new priority with validation for title and color
    #[Route('/api/priority/create', name: 'priority_create', methods: ['POST'])]
    public function createPriority(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        // Decode JSON payload
        $data = json_decode($request->getContent(), true);

        // Ensure data is provided
        if (!$data || empty($data['title']) || empty($data['color'])) {
            return new JsonResponse(['error' => 'Missing title or color'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validate color format (e.g., hex color code)
        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $data['color'])) {
            return new JsonResponse(['error' => 'Invalid color format. Use a hex code like #FFFFFF.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Create a new priority
        $priority = new Priority();
        $priority->setTitle($data['title']);
        $priority->setColor($data['color']);

        // Validate entity (if you add constraints in the Priority entity)
        $errors = $validator->validate($priority);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Save to database
        $em->persist($priority);
        $em->flush();

        // Return success response with the created priority
        return new JsonResponse([
            'message' => 'Priority created successfully',
            'priority' => [
                'id' => $priority->getId(),
                'title' => $priority->getTitle(),
                'color' => $priority->getColor()
            ]
        ], JsonResponse::HTTP_CREATED);
    }

    // Delete priority with safety check
    #[Route('/api/priority/delete/{id}', name: 'priority_delete', methods: ['DELETE'])]
    public function deletePriority(int $id, EntityManagerInterface $em): JsonResponse
    {
        // Find priority by ID
        $priority = $em->getRepository(Priority::class)->find($id);
        if (!$priority) {
            return new JsonResponse(['error' => 'Priority not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Check if priority can be safely deleted
        if (!$priority->canDelete()) { // Ensure canDelete is implemented in Priority entity
            return new JsonResponse(['error' => 'This priority cannot be deleted due to associated tasks.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Remove priority
        $em->remove($priority);
        $em->flush();

        // Return success response
        return new JsonResponse(['message' => 'Priority deleted successfully']);
    }

    // List priorities with a formatted response
    #[Route('/api/priority/list', name: 'priority_list', methods: ['GET'])]
    public function listPriorities(EntityManagerInterface $em): JsonResponse
    {
        $priorities = $em->getRepository(Priority::class)->findAll();

        // Map priorities to a clean structure for JSON
        $data = array_map(fn ($priority) => [
            'id' => $priority->getId(),
            'title' => $priority->getTitle(),
            'color' => $priority->getColor(),
        ], $priorities);

        return new JsonResponse(['priorities' => $data]);
    }
}
