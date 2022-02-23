<?php

declare(strict_types=1);

namespace App\Tools\ExceptionEventHandler;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

interface ExceptionEventHandlerInterface
{
    public static function getSupportedExceptionInterface(): string;

    public function handle(ExceptionEvent $event): void;
}
