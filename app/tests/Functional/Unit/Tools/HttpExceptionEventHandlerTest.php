<?php

declare(strict_types=1);

namespace App\Tests\Functional\Unit\Tools;

use App\Kernel;
use App\Tools\ExceptionEventHandler\HttpExceptionHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Throwable;
use UnexpectedValueException;

final class HttpExceptionEventHandlerTest extends TestCase
{
    private JsonEncoder $jsonEncoder;

    public function setUp(): void
    {
        parent::setUp();

        $this->jsonEncoder = new JsonEncoder();
    }

    /**
     * @dataProvider supportedExceptionProvider
     */
    public function test_valid_response_when_supported_exception_provided(int $expectedCode, Throwable $exception): void
    {
        $loggerStub = $this->createStub(LoggerInterface::class);
        $handler = new HttpExceptionHandler($loggerStub);
        $event = $this->createExceptionEventByException($exception);
        $handler->handle($event);

        $responseStatus = $event->getResponse()->getStatusCode();
        $responseContent = $event->getResponse()->getContent();
        $decodedContent = $this->jsonEncoder->decode($responseContent, $this->jsonEncoder::FORMAT);
        self::assertEquals($expectedCode, $responseStatus);
        self::assertEquals($exception->getMessage(), $decodedContent['error']['message']);
    }

    public function test_default_response_when_unsupported_exception_provided(): void
    {
        $loggerStub = $this->createStub(LoggerInterface::class);
        $handler = new HttpExceptionHandler($loggerStub);
        $exception = new UnexpectedValueException();
        $event = $this->createExceptionEventByException($exception);
        $handler->handle($event);

        $responseStatus = $event->getResponse()->getStatusCode();
        $responseContent = $event->getResponse()->getContent();
        $decodedContent = $this->jsonEncoder->decode($responseContent, $this->jsonEncoder::FORMAT);
        self::assertEquals(500, $responseStatus);
        self::assertEquals('Internal server error', $decodedContent['error']['message']);
    }

    public function supportedExceptionProvider(): array
    {
        return [
            [400, new BadRequestHttpException('Bad request test 123')],
            [404, new NotFoundHttpException('Nothing found test ...')],
        ];
    }

    private function createExceptionEventByException(Throwable $exception): ExceptionEvent
    {
        $kernelStub = $this->createStub(Kernel::class);
        $requestStub = $this->createStub(Request::class);

        return new ExceptionEvent($kernelStub, $requestStub, HttpKernelInterface::MAIN_REQUEST, $exception);
    }
}
