<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookTranslation;
use App\MessageProcessor\Query\BookSearchQuery;
use Doctrine\ORM\Query;

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

        $this->checkIfEntityFetched($book);

        return $book;
    }

    public function getQuerySearchByCriteria(BookSearchQuery $criteria): Query
    {
        $idsBuilder = $this->entityManager->createQueryBuilder();
        $bookIds = $idsBuilder->select('IDENTITY(translation.object)')
            ->from(BookTranslation::class, 'translation')
            ->where($idsBuilder->expr()->eq('translation.field', '\'name\''))
            ->andWhere($idsBuilder->expr()->eq('translation.locale', ':locale'))
            ->andWhere($idsBuilder->expr()->like('translation.content', ':bookName'))
            ->setParameter('locale', $this->request->getLocale())
            ->setParameter('bookName', '%'.$criteria->getBookName().'%')
            ->getQuery()
            ->getSingleColumnResult();

        $bookBuilder = $this->entityManager->createQueryBuilder();

        return $bookBuilder->select('book')
            ->from(Book::class, 'book')
            ->where($bookBuilder->expr()->in('book.id', ':bookIds'))
            ->setParameter('bookIds', $bookIds)
            ->setFirstResult($criteria->getOffset())
            ->setMaxResults($criteria->getLimit())
            ->getQuery();
    }
}
