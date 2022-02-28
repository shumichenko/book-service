<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\MessageProcessor;
use App\MessageProcessor\Query\BookSearchQuery;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/{_locale}/book/search", requirements={"_locale": "ru|en"}, methods={"GET"}, name="book_search")
 */
class BookSearchController
{
    private MessageProcessor $messageProcessor;

    public function __construct(MessageProcessor $messageProcessor)
    {
        $this->messageProcessor = $messageProcessor;
    }

    /**
     * @OA\Tag(name="Book")
     *
     * @OA\Parameter(in="path", name="_locale", required=true, description="Locale language", @OA\Schema(type="string"))
     * @OA\Parameter(in="query", name="book_name", required=true, description="Название книги", @OA\Schema(type="string"))
     * @OA\Parameter(in="query", name="limit", description="Лимит выдачи", @OA\Schema(type="integer"))
     * @OA\Parameter(in="query", name="offset", description="Сдвиг выдачи", @OA\Schema(type="integer"))
     *
     * @OA\Response(
     *     description="Books found or not",
     *     response="200"
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $query = new BookSearchQuery($request);
        $data = $this->messageProcessor->query($query);

        return ResponseMaker::okList($data['books'], $data['books_number'], $query->getLimit(), $query->getOffset());
    }
}
