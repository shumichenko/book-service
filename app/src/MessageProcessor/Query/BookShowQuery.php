<?php

declare(strict_types=1);

namespace App\MessageProcessor\Query;

use App\MessageProcessor\QueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class BookShowQuery implements QueryInterface
{
    /**
     * @Assert\GreaterThan(value=0, message="Invalid book ID")
     */
    private int $bookId;

    public function __construct(Request $request)
    {
        $this->bookId = (int) $request->get('id', 0);
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }
}
