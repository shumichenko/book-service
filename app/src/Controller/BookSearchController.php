<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\MessageProcessor;
use App\MessageProcessor\Query\BookSearchQuery;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/search", methods={"GET"}, name="book_search")
 */
class BookSearchController
{
    private MessageProcessor $messageProcessor;

    public function __construct(MessageProcessor $messageProcessor)
    {
        $this->messageProcessor = $messageProcessor;
    }
//todo
    public function __invoke(Request $request): JsonResponse
    {
        $query = new BookSearchQuery($request);
        $data = $this->messageProcessor->query($query);

        return ResponseMaker::okList($data['books'], $data['books_number'], $query->getLimit(), $query->getOffset());
    }
}
