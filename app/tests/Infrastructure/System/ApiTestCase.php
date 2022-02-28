<?php

declare(strict_types = 1);

namespace App\Tests\Infrastructure\System;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request as BrowserRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

abstract class ApiTestCase extends WebTestCase
{
    protected JsonEncoder $jsonEncoder;
    protected static ?KernelBrowser $client                 = null;
    protected static ?EntityManagerInterface $entityManager = null;

    public function setUp(): void
    {
        $this->jsonEncoder = new JsonEncoder();

        $this->buildClient();

        if (null === self::$entityManager) {
            self::$entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        }

        self::$client->disableReboot();
        self::$entityManager->getConnection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        self::$entityManager->getConnection()->rollBack();
        self::$entityManager->close();

        self::$client        = null;
        self::$entityManager = null;

        parent::tearDown();
    }

    protected function makeRequest(BrowserRequest $request): Response
    {
        self::$client->request(
            $request->getMethod(),
            $request->getUri(),
            $request->getParameters(),
            $request->getFiles(),
            $request->getServer(),
            $request->getContent()
        );

        return self::$client->getResponse();
    }

    protected function responseSuccessTest(Response $response, int $responseCode = Response::HTTP_OK): void
    {
        $responseData = $this->jsonEncoder->decode($response->getContent(), $this->jsonEncoder::FORMAT);

        self::assertSame($responseCode, $response->getStatusCode(), 'raw message: ' . $response->getContent());
        self::assertArrayHasKey('meta', $responseData);
        self::assertArrayHasKey('success', $responseData['meta']);
        self::assertTrue($responseData['meta']['success']);
        self::assertArrayHasKey('data', $responseData);
    }

    protected function responseFailureTest(Response $response, int $responseCode = Response::HTTP_OK): void
    {
        $responseData = $this->jsonEncoder->decode($response->getContent(), $this->jsonEncoder::FORMAT);

        self::assertEquals($responseCode, $response->getStatusCode());
        self::assertFalse($responseData['meta']['success']);
        self::assertArrayHasKey('error', $responseData);
        self::assertArrayHasKey('message', $responseData['error']);
    }

    private function buildClient(): void
    {
        self::ensureKernelShutdown();

        if (null === self::$client) {
            self::$client = self::createClient();
        }

        self::$client->setServerParameter('CONTENT_TYPE', 'application/json');
    }
}
