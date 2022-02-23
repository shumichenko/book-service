<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;
use App\Repository\Exception\EntityNotFoundException;
use function is_null;

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

        if (is_null($author)) {
            throw new EntityNotFoundException('Such entity does not exist');
        }

        return $author;
    }
}
