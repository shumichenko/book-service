<?php

declare(strict_types=1);

namespace App\MessageProcessor\Command;

use App\MessageProcessor\CommandInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class BookCreateCommand implements CommandInterface
{
    /**
     * @Assert\Length(
     *     min=1, max=150,
     *     minMessage="Book name cannot be empty",
     *     maxMessage="Book name length should not be greater than 150 symbols"
     * )
     */
    private string $bookName;

    /**
     * @Assert\GreaterThan(value=0, message="Invalid author ID")
     */
    private int $authorId;

    public function __construct(Request $request)
    {
        $this->bookName = (string) $request->request->get('book_name', '');
        $this->authorId = $request->request->getInt('author_id');
    }

    public function getBookName(): string
    {
        return $this->bookName;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}
