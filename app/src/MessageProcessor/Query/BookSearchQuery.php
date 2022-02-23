<?php

declare(strict_types=1);

namespace App\MessageProcessor\Query;

use App\MessageProcessor\QueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class BookSearchQuery implements QueryInterface
{
    /**
     * @Assert\NotBlank(message="Book name cannot be empty")
     */
    private string $bookName;
    private int $limit;
    private int $offset;

    public function __construct(Request $request)
    {
        $this->bookName = (string) $request->query->get('book_name', '');
        $this->limit = $request->query->getInt('limit', 50);
        $this->offset = $request->query->getInt('offset');
    }

    public function getBookName(): string
    {
        return $this->bookName;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
