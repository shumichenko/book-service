<?php

declare(strict_types=1);

namespace App\EntityMapper;

use App\Entity\Book;

class BookMapper
{
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
        return [
            'Id'   => $book->getId(),
            'Name' => $book->getName(),
        ];
    }
}
