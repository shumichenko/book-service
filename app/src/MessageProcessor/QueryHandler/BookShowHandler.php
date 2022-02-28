<?php

declare(strict_types=1);

namespace App\MessageProcessor\QueryHandler;

use App\EntityMapper\BookShowMapper;
use App\MessageProcessor\Query\BookShowQuery;
use App\Repository\BookRepository;
use App\Repository\Exception\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BookShowHandler implements MessageHandlerInterface
{
    private BookRepository $bookRepository;
    private BookShowMapper $bookMapper;

    public function __construct(BookRepository $bookRepository, BookShowMapper $bookMapper)
    {
        $this->bookRepository = $bookRepository;
        $this->bookMapper = $bookMapper;
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(BookShowQuery $query): array
    {
        try {
            $book = $this->bookRepository->getById($query->getBookId());
        } catch (EntityNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $this->bookMapper->mapOne($book);
    }
}
