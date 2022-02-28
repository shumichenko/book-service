<?php

declare(strict_types = 1);

namespace App\Tests\Functional\System\Book;

use App\Tests\Infrastructure\System\ApiTestCase;
use Symfony\Component\BrowserKit\Request as BrowserRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function sprintf;

final class BookSearchTest extends ApiTestCase
{
    private const ENDPOINT = '/%s/book/search';

    public function test_book_list_when_books_found(): void
    {
        $request = new BrowserRequest($this->buildEndpoint(), Request::METHOD_GET, ['book_name' => 't']);
        $response = $this->makeRequest($request);
        $decodedContent = $this->jsonEncoder->decode($response->getContent(), $this->jsonEncoder::FORMAT);

        $this->responseSuccessTest($response);
        $books = $decodedContent['data'] ?? [];
        foreach ($books as $book) {
            self::assertArrayHasKey('Id', $book);
            self::assertArrayHasKey('Name', $book);
            self::assertArrayHasKey('Author', $book);
        }
    }

    public function test_empty_data_when_no_books_found(): void
    {
        $request = new BrowserRequest(
            $this->buildEndpoint(),
            Request::METHOD_GET,
            ['book_name' => '11test_cannot_be_found_test_book_name_non_existent_book2345']
        );
        $response = $this->makeRequest($request);
        $decodedContent = $this->jsonEncoder->decode($response->getContent(), $this->jsonEncoder::FORMAT);

        $this->responseSuccessTest($response);
        $books = $decodedContent['data'];
        self::assertEmpty($books);
    }

    public function test_invalid_request_data_error_when_invalid_book_name_provided(): void
    {
        $request = new BrowserRequest(
            $this->buildEndpoint(),
            Request::METHOD_GET,
            ['book_name' => '']
        );
        $response = $this->makeRequest($request);

        $this->responseFailureTest($response, Response::HTTP_BAD_REQUEST);
    }


    private function buildEndpoint(string $locale = 'en'): string
    {
        return sprintf(self::ENDPOINT, $locale);
    }
}
