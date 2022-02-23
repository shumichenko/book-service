<?php

declare(strict_types = 1);

namespace App\Listener;

use App\Tools\ExceptionEventHandler\ExceptionEventHandlerFactory;
use App\Tools\ResponseMaker;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;
use function get_class;

class ExceptionsListener implements EventSubscriberInterface
{
    private ExceptionEventHandlerFactory $exceptionHandlerFactory;
    private LoggerInterface $logger;

    public function __construct(ExceptionEventHandlerFactory $exceptionHandlerFactory, LoggerInterface $logger)
    {
        $this->exceptionHandlerFactory = $exceptionHandlerFactory;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        try {
            $exceptionHandler = $this->exceptionHandlerFactory->getByExceptionName(get_class($exception));
        } catch (Throwable $factoryException) {
            $this->logger->log(Logger::CRITICAL, $exception->getMessage(), $exception->getTrace());
            $event->setResponse(ResponseMaker::internalServerError());

            return;
        }

        $exceptionHandler->handle($event);
    }

}
