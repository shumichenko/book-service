<?php

declare(strict_types=1);

namespace App\MessageProcessor\QueryHandler;

use App\EntityMapper\BookMapper;
use App\MessageProcessor\Query\BookShowQuery;
use App\Repository\BookRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BookShowHandler implements MessageHandlerInterface
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
    public function __invoke(BookShowQuery $query): array
    {
        $book = $this->bookRepository->getById($query->getBookId());

        return $this->bookMapper->mapOne($book);
    }
}
