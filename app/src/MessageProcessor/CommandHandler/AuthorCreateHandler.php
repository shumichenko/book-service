<?php

declare(strict_types=1);

namespace App\MessageProcessor\CommandHandler;

use App\Entity\Author;
use App\MessageProcessor\Command\AuthorCreateCommand;
use App\Repository\AuthorRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AuthorCreateHandler implements MessageHandlerInterface
{
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(AuthorCreateCommand $command): int
    {
        $author = new Author($command->getAuthorName());
        $savedAuthor = $this->authorRepository->saveOne($author);

        return $savedAuthor->getId();
    }
}
