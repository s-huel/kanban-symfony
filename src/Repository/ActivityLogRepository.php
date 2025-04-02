<?php

namespace App\Repository;

use App\Entity\ActivityLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityLog::class);
    }

    public function findPaginated(int $page, int $limit, array $filters): \Doctrine\ORM\Tools\Pagination\Paginator
    {
        $query = $this->createFilterQueryBuilder($filters)
            ->orderBy('al.timestamp', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new \Doctrine\ORM\Tools\Pagination\Paginator($query);
    }

    private function createFilterQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('al');

        if ($filters['action']) {
            $qb->andWhere('al.action = :action')
                ->setParameter('action', $filters['action']);
        }

        if ($filters['entity_type']) {
            $qb->andWhere('al.entityType = :entityType')
                ->setParameter('entityType', $filters['entity_type']);
        }

        if ($filters['user_id']) {
            $qb->andWhere('al.user = :userId')
                ->setParameter('userId', $filters['user_id']);
        }

        if ($filters['start_date']) {
            $qb->andWhere('al.timestamp >= :startDate')
                ->setParameter('startDate', new \DateTimeImmutable($filters['start_date']));
        }

        if ($filters['end_date']) {
            $qb->andWhere('al.timestamp <= :endDate')
                ->setParameter('endDate', new \DateTimeImmutable($filters['end_date']));
        }

        return $qb;
    }
}