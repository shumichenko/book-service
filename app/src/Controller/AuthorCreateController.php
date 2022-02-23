<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\Command\AuthorCreateCommand;
use App\MessageProcessor\MessageProcessor;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/author/create", methods={"POST"}, name="author_create")
 */
class AuthorCreateController
{
    private MessageProcessor $messageProcessor;

    public function __construct(MessageProcessor $messageProcessor)
    {
        $this->messageProcessor = $messageProcessor;
    }
//todo
    public function __invoke(Request $request): JsonResponse
    {
        $createdEntityId = $this->messageProcessor->command(new AuthorCreateCommand($request));

        return ResponseMaker::created($createdEntityId);
    }
}
