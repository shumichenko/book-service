<?php

declare(strict_types=1);

namespace App\MessageProcessor\CommandHandler;

use App\Entity\Book;
use App\MessageProcessor\Command\BookCreateCommand;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\Exception\EntityNotFoundException;
use App\Repository\Exception\RepositoryExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BookCreateHandler implements MessageHandlerInterface
{
    private AuthorRepository $authorRepository;
    private BookRepository $bookRepository;

    public function __construct(AuthorRepository $authorRepository, BookRepository $bookRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(BookCreateCommand $command): int
    {
        try {
            $author = $this->authorRepository->getById($command->getAuthorId());
        } catch (EntityNotFoundException $exception) {
            throw new NotFoundHttpException('Author not found');
        }

        $book = new Book($command->getBookName());
        $book->addAuthor($author);
        $savedBook = $this->bookRepository->saveOne($book);

        return $savedBook->getId();
    }
}
