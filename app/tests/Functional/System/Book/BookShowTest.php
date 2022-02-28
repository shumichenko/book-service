<?php

declare(strict_types = 1);

namespace App\Tests\Functional\System\Book;

use App\Tests\Infrastructure\System\ApiTestCase;
use Symfony\Component\BrowserKit\Request as BrowserRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function sprintf;

final class BookShowTest extends ApiTestCase
{
    private const ENDPOINT = '/%s/book/%d';

    /**
     * @dataProvider bookIdProvider
     */
    public function test_book_data_when_book_found(int $bookId): void
    {
        $request = new BrowserRequest($this->buildEndpoint($bookId), Request::METHOD_GET);
        $response = $this->makeRequest($request);
        $decodedContent = $this->jsonEncoder->decode($response->getContent(), $this->jsonEncoder::FORMAT);

        $this->responseSuccessTest($response);
        self::assertArrayHasKey('Id', $decodedContent['data']);
        self::assertArrayHasKey('Name', $decodedContent['data']);
    }

    public function test_invalid_request_data_error_when_invalid_book_id_provided(): void
    {
        $request = new BrowserRequest($this->buildEndpoint(0), Request::METHOD_GET);
        $response = $this->makeRequest($request);

        $this->responseFailureTest($response, Response::HTTP_BAD_REQUEST);
    }

    public function test_book_not_found_when_non_existent_book_id_provided(): void
    {
        $request = new BrowserRequest($this->buildEndpoint(999999999), Request::METHOD_GET);
        $response = $this->makeRequest($request);

        $this->responseFailureTest($response, Response::HTTP_NOT_FOUND);
    }

    public function bookIdProvider(): array
    {
        return [
            [1], [20], [300], [4000], [9999],
        ];
    }

    private function buildEndpoint(int $bookId, string $locale = 'en'): string
    {
        return sprintf(self::ENDPOINT, $locale, $bookId);
    }
}
