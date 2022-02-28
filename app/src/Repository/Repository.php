<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EntityInterface;
use App\Repository\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class Repository
{
    protected EntityManagerInterface $entityManager;
    protected Request $request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->request = $requestStack->getCurrentRequest();
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

    protected function checkIfEntityFetched(?EntityInterface $entity): void
    {
        if (is_null($entity)) {
            throw new EntityNotFoundException('Such entity does not exist');
        }
    }
}
