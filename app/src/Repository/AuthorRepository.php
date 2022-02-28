<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;

class AuthorRepository extends Repository
{
    public function getById(int $id): Author
    {
        $author = $this->entityManager->createQueryBuilder()
            ->select('author')
            ->from(Author::class, 'author')
            ->where('author.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        $this->checkIfEntityFetched($author);

        return $author;
    }
}
