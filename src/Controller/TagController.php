<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\TagTask;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tag')]
class TagController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    // Endpoint to create a new tag
    #[Route('/create', name: 'tag_create', methods: ['POST'])]
    public function createTag(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['title']) || !is_string($data['title'])) {
            return new JsonResponse(['error' => 'A valid title is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $tag = new Tag();
        $tag->setTitle($data['title']);
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Tag created', 'tag' => ['id' => $tag->getId(), 'title' => $tag->getTitle()]], JsonResponse::HTTP_CREATED);
    }

    // Endpoint to associate a tag with a task, creating the tag if necessary
    // Endpoint to associate a tag with a task, creating the tag if necessary
    #[Route('/{taskId}/add', name: 'tag_add_to_task', methods: ['POST'])]
    public function addTagToTask(int $taskId, Request $request): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (empty($data['title']) || !is_string($data['title'])) {
            return new JsonResponse(['error' => 'A valid tag title is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Check if the tag exists, otherwise create it
        $tag = $this->entityManager->getRepository(Tag::class)->findOneBy(['title' => $data['title']]);
        if (!$tag) {
            $tag = new Tag();
            $tag->setTitle($data['title']);
            $this->entityManager->persist($tag);
        }

        // Check if the association already exists
        $existingTagTask = $this->entityManager->getRepository(TagTask::class)
            ->findOneBy(['task' => $task, 'tag' => $tag]);

        if ($existingTagTask) {
            return new JsonResponse(['message' => 'Tag is already associated with the task'], JsonResponse::HTTP_CONFLICT);
        }

        // Create the association
        $tagTask = new TagTask();
        $tagTask->setTask($task);
        $tagTask->setTag($tag);
        $this->entityManager->persist($tagTask);

        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Tag added to task successfully',
            'tag' => ['id' => $tag->getId(), 'title' => $tag->getTitle()],
        ]);
    }


    // Endpoint to remove a tag from a task
    #[Route('/{taskId}/remove/{tagId}', name: 'tag_remove_from_task', methods: ['DELETE'])]
    public function removeTagFromTask(int $taskId, int $tagId): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        $tag = $this->entityManager->getRepository(Tag::class)->find($tagId);

        if (!$task || !$tag) {
            return new JsonResponse(['error' => 'Task or tag not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $tagTask = $this->entityManager->getRepository(TagTask::class)
            ->findOneBy(['task' => $task, 'tag' => $tag]);

        if (!$tagTask) {
            return new JsonResponse(['error' => 'Tag association not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($tagTask);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Tag removed from task']);
    }

    // Endpoint to search for tags by a partial title match
    #[Route('/search', name: 'tag_search', methods: ['GET'])]
    public function searchTags(Request $request): JsonResponse
    {
        $query = $request->query->get('query', '');
        $limit = (int) $request->query->get('limit', 10);

        if (!is_string($query) || empty(trim($query))) {
            return new JsonResponse([], JsonResponse::HTTP_OK);
        }

        $tags = $this->entityManager->getRepository(Tag::class)
            ->createQueryBuilder('t')
            ->where('LOWER(t.title) LIKE LOWER(:query)')
            ->setParameter('query', '%' . trim($query) . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $result = array_map(fn($tag) => ['id' => $tag->getId(), 'title' => $tag->getTitle()], $tags);

        return new JsonResponse($result);
    }

}
