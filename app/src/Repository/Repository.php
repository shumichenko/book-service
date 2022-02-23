<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class Repository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveOne(EntityInterface $entity): EntityInterface
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param array<EntityInterface> $entities
     */
    public function saveMany(array $entities): void
    {
        foreach ($entities as $entity) {
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();
    }

    /**
     * @return array<EntityInterface>
     */
    public function fetchEntitiesByQuery(Query $query): array
    {
        return $query->getResult();
    }

    public function countEntitiesByQuery(Query $query, bool $fetchJoinCollection = true): int
    {
        $paginator = new Paginator($query, $fetchJoinCollection);

        return $paginator->count();
    }
}
