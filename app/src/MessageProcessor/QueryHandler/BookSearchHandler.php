<?php

declare(strict_types=1);

namespace App\MessageProcessor\QueryHandler;

use App\EntityMapper\BookMapper;
use App\MessageProcessor\Query\BookSearchQuery;
use App\Repository\BookRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BookSearchHandler implements MessageHandlerInterface
{
    private BookRepository $bookRepository;
    private BookMapper $bookMapper;

    public function __construct(BookRepository $bookRepository, BookMapper $bookMapper)
    {
        $this->bookRepository = $bookRepository;
        $this->bookMapper = $bookMapper;
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(BookSearchQuery $query): array
    {
        $databaseQuery = $this->bookRepository->getQuerySearchByCriteria($query);
        $books = $this->bookRepository->fetchEntitiesByQuery($databaseQuery);
        $booksNumber = $this->bookRepository->countEntitiesByQuery($databaseQuery);

        return [
            'books_number' => $booksNumber,
            'books' => $this->bookMapper->mapMany($books),
        ];
    }
}
