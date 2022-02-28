<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\MessageProcessor;
use App\MessageProcessor\Query\BookShowQuery;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/{_locale}/book/{id}", requirements={"id":"\d+", "_locale": "ru|en"}, methods={"GET"}, name="book_show")
 */
class BookShowController
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
     * @OA\Parameter(in="path", name="id", required=true, description="Book identifier", @OA\Schema(type="integer"))
     *
     * @OA\Response(
     *     description="Book found",
     *     response="200"
     * )
     * @OA\Response(
     *     description="Book not found",
     *     response="404"
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $book = $this->messageProcessor->query(new BookShowQuery($request));

        return ResponseMaker::ok($book);
    }
}
