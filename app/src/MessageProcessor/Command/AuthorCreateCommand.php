<?php

declare(strict_types=1);

namespace App\MessageProcessor\Command;

use App\MessageProcessor\CommandInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class AuthorCreateCommand implements CommandInterface
{
    /**
     * @Assert\Length(
     *     min=1, max=100,
     *     minMessage="Author name cannot be empty",
     *     maxMessage="Author name length should not be greater than 100 symbols"
     * )
     */
    private string $authorName;

    public function __construct(Request $request)
    {
        $this->authorName = (string) $request->request->get('author_name', '');
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }
}
