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

    private int $bookId;

    /**
     * @Assert\GreaterThan(value=0, message="Invalid author ID")
     */
    private int $authorId;

    public function __construct(Request $request)
    {
        $this->bookName = (string) $request->request->get('book_name', '');
        $this->bookId =  $request->request->getInt('book_id');
        $this->authorId = $request->request->getInt('author_id');
    }

    public function getBookName(): string
    {
        return $this->bookName;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}
