<?php

namespace App\Service;

use App\Entity\ActivityLog;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\UnicodeString;

class ActivityLogService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    public function logActivity(string $action, object $entity, ?array $changes = null): void
    {
        $activityLog = new ActivityLog();
        $activityLog->setAction($action);
        $activityLog->setEntityType($this->getEntityType($entity));
        $activityLog->setTimestamp(new \DateTime());

        if ($action === 'Updated' && empty($changes)) {
            return;
        }

        $activityLog->setDetails($this->buildDetails($action, $entity, $changes));
        $activityLog->setUser($this->getCurrentUser());

        $this->entityManager->persist($activityLog);
        $this->entityManager->flush();
    }

    private function getEntityType(object $entity): string
    {
        return (new UnicodeString(get_class($entity)))
            ->afterLast('\\')
            ->toString();
    }

    private function buildDetails(string $action, object $entity, ?array $changes): string
    {
        return match($action) {
            'Deleted', 'Added' => $this->formatBasicDetails($entity),
            'Updated' => $this->formatChangeDetails($changes),
            default => 'Action performed'
        };
    }

    private function formatBasicDetails(object $entity): string
    {
        $details = [];

        if (method_exists($entity, 'getId')) {
            $details[] = 'ID: '.$entity->getId();
        }

        if (method_exists($entity, 'getTitle')) {
            $details[] = 'Title: '.$entity->getTitle();
        }

        return implode('; ', $details) ?: 'No details available';
    }

    private function formatChangeDetails(?array $changes): string
    {
        if (empty($changes)) {
            return 'No changes recorded';
        }

        $formatted = [];
        foreach ($changes as $property => [$old, $new]) {
            if ($old !== $new) {  // Only log if value has changed
                $formatted[] = ucfirst($property) . ": '$old' → '$new'";
            }
        }

        return implode('; ', $formatted);
    }

    private function formatPropertyName(string $property): string
    {
        return (new UnicodeString($property))
            ->snake()
            ->replace('_', ' ')
            ->title()
            ->toString();
    }

    private function getCurrentUser(): ?User
    {
        $user = $this->security->getUser();
        return $user instanceof User ? $user : null;
    }
}