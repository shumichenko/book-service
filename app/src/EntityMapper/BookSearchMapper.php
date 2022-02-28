<?php

declare(strict_types=1);

namespace App\EntityMapper;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BookSearchMapper
{
    private Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param array<Book> $books
     * @return array<array<string, mixed>>
     */
    public function mapMany(array $books): array
    {
        $mappedBooks = [];
        foreach ($books as $book) {
            $mappedBooks[] = $this->mapOne($book);
        }

        return $mappedBooks;
    }

    /**
     * @return array<string, mixed>
     */
    public function mapOne(Book $book): array
    {
        $nameTranslation = $book->getTranslation($this->request->getLocale(), 'name');

        return [
            'Id'     => $book->getId(),
            'Name'   => $nameTranslation ? $nameTranslation->getContent() : '',
            'Author' => $this->mapAuthors($book->getAuthors()),
        ];
    }

    /**
     * @param Collection<Author> $authors
     *
     * @return array<string, mixed>
     */
    private function mapAuthors(Collection $authors): array
    {
        $mappedAuthors = [];
        foreach ($authors as $author) {
            $mappedAuthors[] = $this->mapAuthor($author);
        }

        return $mappedAuthors;
    }

    private function mapAuthor(Author $author): array
    {
        return [
            'Id'   => $author->getId(),
            'Name' => $author->getName(),
        ];
    }
}
