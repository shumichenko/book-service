<?php

declare(strict_types=1);

namespace App\EntityMapper;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BookShowMapper
{
    private Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @return array<string, mixed>
     */
    public function mapOne(Book $book): array
    {
        $nameTranslation = $book->getTranslation($this->request->getLocale(), 'name');

        return [
            'Id'   => $book->getId(),
            'Name' => $nameTranslation ? $nameTranslation->getContent() : '',
        ];
    }
}
