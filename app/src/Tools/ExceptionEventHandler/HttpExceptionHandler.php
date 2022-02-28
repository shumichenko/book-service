<?php

declare(strict_types=1);

namespace App\Tools\ExceptionEventHandler;

use App\Tools\ResponseMaker;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;
use function get_class;

class HttpExceptionHandler implements ExceptionEventHandlerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSupportedExceptionInterface(): string
    {
        return HttpExceptionInterface::class;
    }

    public function handle(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        switch (get_class($exception)) {
            case BadRequestHttpException::class:
                $response = ResponseMaker::badRequest($exception->getMessage());

                break;
            case NotFoundHttpException::class:
                $response = ResponseMaker::notFound($exception->getMessage());

                break;
            default:
                $response = ResponseMaker::internalServerError();

                break;
        }

        $this->log($response->getStatusCode(), $exception);
        $event->setResponse($response);
    }

    private function log(int $statusCode, Throwable $exception): void
    {
        switch ($statusCode) {
            case 400:
                $level = Logger::WARNING;

                break;
            case 401:
            case 403:
                $level = Logger::NOTICE;

                break;
            case 405:
            case 404:
                $level = Logger::INFO;

                break;
            case 500:
            case 503:
            default:
                $level = Logger::CRITICAL;

                break;
        }

        $this->logger->log($level, $exception->getMessage(), $exception->getTrace());
    }
}
