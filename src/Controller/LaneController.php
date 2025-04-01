<?php

namespace App\Controller;

use App\DTO\UpdateLaneRequestDTO;
use App\Entity\Lane;
use App\Repository\LaneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/lane')]
#[IsGranted('ROLE_USER')]
class LaneController extends AbstractController
{
    #[Route('/', name: 'lane_list', methods: ['GET'])]
    public function index(LaneRepository $laneRepository): JsonResponse
    {
        $lanes = $laneRepository->findAll();

        $data = [];
        foreach ($lanes as $lane) {
            $data[] = [
                'id' => $lane->getId(),
                'title' => $lane->getTitle(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/create', name: 'lane_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['title'])) {
            return new JsonResponse(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        $lane = new Lane();
        $lane->setTitle($data['title']);

        $entityManager->persist($lane);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $lane->getId(),
            'title' => $lane->getTitle(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'lane_update', methods: ['PUT'])]
    public function update(
        Lane $lane,
        #[MapRequestPayload] UpdateLaneRequestDTO $updateLaneRequestDTO,
        EntityManagerInterface $entityManager)
    {
        $oldTitle = $lane->getTitle();

        $lane->setTitle($updateLaneRequestDTO->title);

        try {
            $entityManager->flush();

            return new JsonResponse([
                'id' => $lane->getId(),
                'title' => $lane->getTitle(),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to update lane: '.$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/delete/{id}', name: 'lane_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $lane = $entityManager->getRepository(Lane::class)->find($id);

        if (!$lane) {
            return new JsonResponse(['error' => 'Lane not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($lane);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Lane deleted successfully'], Response::HTTP_OK);
    }
}
