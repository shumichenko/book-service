<?php

declare(strict_types=1);

namespace App\Tools;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseMaker
{
    /**
     * @param array<string,mixed> $dataSet
     */
    public static function ok(array $dataSet): JsonResponse
    {
        return self::getResponse([
            'meta' => ['success' => true],
            'data' => $dataSet,
        ], Response::HTTP_OK);
    }

    /**
     * @param array<array<string,mixed>> $list
     */
    public static function okList(array $list, int $total, int $limit, int $offset): JsonResponse
    {
        return self::getResponse([
            'meta' => [
                'success' => true,
                'total'   => $total,
                'limit'   => $limit,
                'offset'  => $offset,
            ],
            'data' => $list,
        ], Response::HTTP_OK);
    }

    public static function created($id): JsonResponse
    {
        return self::getResponse([
            'meta' => ['success' => true],
            'data' => ['id' => $id],
        ], Response::HTTP_CREATED);
    }

    public static function badRequest(string $message): JsonResponse
    {
        return self::getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => $message],
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array<string,string> $errors
     */
    public static function invalidRequestData(array $errors): JsonResponse
    {
        return self::getResponse([
            'meta'  => ['success' => false],
            'error' => [
                'message' => 'Invalid request data',
                'details' => $errors,
            ],
        ], Response::HTTP_BAD_REQUEST);
    }

    public static function notFound(string $message = 'Not found'): JsonResponse
    {
        return self::getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => $message],
        ], Response::HTTP_NOT_FOUND);
    }

    public static function methodNotAllowed(): JsonResponse
    {
        return self::getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Method not allowed'],
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public static function internalServerError(): JsonResponse
    {
        return self::getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Internal server error'],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function serviceUnavailable(): JsonResponse
    {
        return self::getResponse([
            'meta'  => ['success' => false],
            'error' => ['message' => 'Service unavailable'],
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function getResponse(array $data, int $status): JsonResponse
    {
        return new JsonResponse($data, $status);
    }
}
