<?php

declare(strict_types=1);

namespace App\Controller;

use App\MessageProcessor\Command\AuthorCreateCommand;
use App\MessageProcessor\MessageProcessor;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

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

    /**
     * @OA\Tag(name="Book")
     *
     * @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="author_name",
     *                 type="string",
     *                 required=true
     *             ),
     *             example={"author_name": "Daphna deu Morier"}
     *         )
     *     )
     * )
     *
     * @OA\Response(
     *     description="Author successful created",
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
        $createdEntityId = $this->messageProcessor->command(new AuthorCreateCommand($request));

        return ResponseMaker::created($createdEntityId);
    }
}
