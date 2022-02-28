<?php

declare(strict_types=1);

namespace App\MessageProcessor\CommandHandler;

use App\Entity\Book;
use App\Entity\BookTranslation;
use App\MessageProcessor\Command\BookCreateCommand;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\Exception\EntityNotFoundException;
use App\Repository\Exception\RepositoryExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BookCreateHandler implements MessageHandlerInterface
{
    private AuthorRepository $authorRepository;
    private BookRepository $bookRepository;
    private Request $request;

    public function __construct(AuthorRepository $authorRepository, BookRepository $bookRepository, RequestStack $requestStack)
    {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function __invoke(BookCreateCommand $command): int
    {
        try {
            $author = $this->authorRepository->getById($command->getAuthorId());
        } catch (EntityNotFoundException $exception) {
            throw new NotFoundHttpException('Author not found');
        }

        if ($command->getBookId() > 0) {
            $book = $this->fetchBookId($command->getBookId());
        } else {
            $book = new Book();
        }

        $nameTranslation = new BookTranslation($this->request->getLocale(), 'name', $command->getBookName());
        $book->addTranslation($nameTranslation);
        $book->addAuthor($author);
        $savedBook = $this->bookRepository->saveOne($book);

        return $savedBook->getId();
    }

    private function fetchBookId(int $bookId): Book
    {
        try {
            $book = $this->bookRepository->getById($bookId);
        } catch (EntityNotFoundException $exception) {
            throw new NotFoundHttpException('You were trying to update the book which does not exist');
        }

        return $book;
    }
}
