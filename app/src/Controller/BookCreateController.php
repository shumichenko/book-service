<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\Command\BookCreateCommand;
use App\MessageProcessor\MessageProcessor;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/{_locale}/book/create", requirements={"_locale": "ru|en"}, methods={"POST"}, name="book_create")
 */
class BookCreateController
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
     * @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="book_name",
     *                 type="string",
     *                 required=true
     *             ),
     *             @OA\Property(
     *                 property="book_id",
     *                 type="interger",
     *                 required=false
     *             ),
     *             @OA\Property(
     *                 property="author_id",
     *                 type="interger",
     *                 required=true
     *             ),
     *             example={"book_name": "Rebecca", "book_id": 1, "author_id": 300}
     *         )
     *     )
     * )
     *
     * @OA\Response(
     *     description="Book successful created",
     *     response="200"
     * )
     * @OA\Response(
     *     description="Request parameters are invalid",
     *     response="400"
     * )
     * @OA\Response(
     *     description="Author not found or book not found if specified",
     *     response="404"
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $createdEntityId = $this->messageProcessor->command(new BookCreateCommand($request));

        return ResponseMaker::created($createdEntityId);
    }
}
