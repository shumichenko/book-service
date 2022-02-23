<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\MessageProcessor\Query\BookSearchQuery;
use App\Repository\Exception\EntityNotFoundException;
use Doctrine\ORM\Query;
use function sprintf;
use function is_null;

class BookRepository extends Repository
{
    public function getById(int $bookId): Book
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $book = $queryBuilder->select('book')
            ->from(Book::class, 'book')
            ->where($queryBuilder->expr()->eq('book.id', ':bookId'))
            ->setParameter('bookId', $bookId)
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($book)) {
            throw new EntityNotFoundException('Such entity does not exist');
        }

        return $book;
    }

    public function getQuerySearchByCriteria(BookSearchQuery $criteria): Query
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $bookName = sprintf('%%%s%%', $criteria->getBookName());

        return $queryBuilder->select('book')
            ->from(Book::class, 'book')
            ->where($queryBuilder->expr()->like('book.name', ':bookName'))
            ->setParameter('bookName', $bookName)
            ->setFirstResult($criteria->getOffset())
            ->setMaxResults($criteria->getLimit())
            ->getQuery();
    }
}
