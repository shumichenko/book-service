<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\Command\BookCreateCommand;
use App\MessageProcessor\MessageProcessor;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/create", methods={"POST"}, name="book_create")
 */
class BookCreateController
{
    private MessageProcessor $messageProcessor;

    public function __construct(MessageProcessor $messageProcessor)
    {
        $this->messageProcessor = $messageProcessor;
    }
//todo
    public function __invoke(Request $request): JsonResponse
    {
        $createdEntityId = $this->messageProcessor->command(new BookCreateCommand($request));

        return ResponseMaker::created($createdEntityId);
    }
}
