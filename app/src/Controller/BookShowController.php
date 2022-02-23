<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\MessageProcessor;
use App\MessageProcessor\Query\BookShowQuery;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/{id}", requirements={"id":"\d+"}, methods={"GET"}, name="book_show")
 */
class BookShowController
{
    private MessageProcessor $messageProcessor;

    public function __construct(MessageProcessor $messageProcessor)
    {
        $this->messageProcessor = $messageProcessor;
    }
//todo
    public function __invoke(Request $request): JsonResponse
    {
        $book = $this->messageProcessor->query(new BookShowQuery($request));

        return ResponseMaker::ok($book);
    }
}
