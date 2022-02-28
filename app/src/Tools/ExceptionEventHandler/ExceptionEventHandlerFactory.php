<?php

declare(strict_types=1);

namespace App\Tools\ExceptionEventHandler;

use DomainException;
use Psr\Container\ContainerInterface;
use Throwable;
use function class_implements;
use function in_array;
use function sprintf;

class ExceptionEventHandlerFactory
{
    /**
     * @var array<class-string<ExceptionEventHandlerInterface>>
     */
    private const EXCEPTION_HANDLERS = [
        HttpExceptionHandler::class,
        AppExceptionHandler::class,
    ];

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param class-string<Throwable> $exceptionName
     */
    public function getByExceptionName(string $exceptionName): ExceptionEventHandlerInterface
    {
        foreach (self::EXCEPTION_HANDLERS as $className) {
            if ($this->isExceptionSupportedByClass($className, $exceptionName)) {
                return $this->container->get($className);
            }
        }

        throw new DomainException(sprintf('Exception handler was not found for class %s', $exceptionName));
    }

    /**
     * @param class-string<ExceptionEventHandlerInterface> $className
     * @param class-string<Throwable> $exceptionName
     */
    private function isExceptionSupportedByClass(string $className, string $exceptionName): bool
    {
        return in_array($className::getSupportedExceptionInterface(), class_implements($exceptionName), true);
    }
}
