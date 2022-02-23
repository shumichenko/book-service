<?php

declare(strict_types=1);

namespace App\Tools\ExceptionEventHandler;

use App\Exception\AppExceptionInterface;
use App\Exception\InvalidRequestDataException;
use App\Tools\ResponseMaker;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use function get_class;

class AppExceptionHandler implements ExceptionEventHandlerInterface
{
    public static function getSupportedExceptionInterface(): string
    {
        return AppExceptionInterface::class;
    }

    public function handle(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        switch (get_class($exception)) {
            case InvalidRequestDataException::class:
                $response = ResponseMaker::invalidRequestData($exception->getErrors());

                break;
            default:
                $response = ResponseMaker::internalServerError();

                break;
        }

        $event->setResponse($response);
    }
}
