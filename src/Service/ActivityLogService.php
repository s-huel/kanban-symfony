<?php

namespace App\Service;

use App\Entity\ActivityLog;
use App\Entity\Lane;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\UnicodeString;

class ActivityLogService
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function logActivity(string $action, Task|Lane $entity, ?array $changes = null): void
    {
        $activityLog = new ActivityLog();
        $activityLog->setAction($action);
        $activityLog->setEntityType((new UnicodeString(get_class($entity)))->afterLast('\\')->toString());
        $activityLog->setTimestamp(new \DateTime());

        $details = '';

        switch ($action) {
            case 'Deleted':
            case 'Added':
                $details = sprintf('Id: %d; Title: %s', $entity->getId(), $entity->getTitle());
                break;

            case 'Updated':
                if ($changes) {
                    $changeDetails = [];
                    foreach ($changes as $key => [$old, $new]) {
                        $changeDetails[] = sprintf('%s: %s -> %s', ucfirst($key), $old, $new);
                    }
                    $details = implode('; ', $changeDetails);
                }
                break;

        }

        $activityLog->setDetails($details);

        $user = $this->security->getUser();
        if ($user instanceof User) {
            $activityLog->setUser($user);
        }

        $this->entityManager->persist($activityLog);
        $this->entityManager->flush();
    }
}
