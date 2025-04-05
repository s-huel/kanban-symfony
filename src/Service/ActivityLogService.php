<?php

namespace App\Service;

use App\Entity\ActivityLog;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\UnicodeString;

// Logs user activity in the application
class ActivityLogService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    // Creates a new activity log entry
    public function logActivity(string $action, object $entity, ?array $changes = null): void
    {
        $activityLog = new ActivityLog();
        $activityLog->setAction($action);
        $activityLog->setEntityType($this->getEntityType($entity));
        $activityLog->setTimestamp(new \DateTime());

        // Prevent logging pointless updates
        if ($action === 'Updated' && empty($changes)) {
            return;
        }

        $activityLog->setDetails($this->buildDetails($action, $entity, $changes));
        $activityLog->setUser($this->getCurrentUser());

        $this->entityManager->persist($activityLog);
        $this->entityManager->flush();
    }

    // Extracts a simple name from an entity's class name (e.g. "App\Entity\Task" becomes "Task")
    private function getEntityType(object $entity): string
    {
        return (new UnicodeString(get_class($entity)))
            ->afterLast('\\')
            ->toString();
    }

    // Constructs a log message based on the type of action
    private function buildDetails(string $action, object $entity, ?array $changes): string
    {
        return match($action) {
            'Deleted', 'Added' => $this->formatBasicDetails($entity),
            'Updated' => $this->formatChangeDetails($changes),
            default => 'Action performed'
        };
    }

    // Basic string for added or deleted entities
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


    // Change string for updated entities (e.g. "Title: 'Old' → 'New'")
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

    // Returns the current logged in user if available
    private function getCurrentUser(): ?User
    {
        $user = $this->security->getUser();
        return $user instanceof User ? $user : null;
    }
}