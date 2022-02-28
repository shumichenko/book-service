<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function json_decode;
use function json_last_error;
use function strpos;
use function strtolower;
use function is_int;

class RequestListener implements EventSubscriberInterface
{
    private Request $request;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest'],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $this->request = $event->getRequest();
        if ($this->hasContent()) {
            $this->request->request->replace($this->getJsonDecoded());
        }

        $this->request->setLocale($this->request->get('_locale', 'en'));
    }

    private function hasContent(): bool
    {
        return $this->request->getContent() && $this->isContentTypeJson();
    }

    private function isContentTypeJson(): bool
    {
        $contentType = $this->request->headers->get('content-type') ?? '';

        return is_int(strpos(strtolower($contentType), 'application/json'));
    }

    private function getJsonDecoded(): array
    {
        $data = json_decode($this->request->getContent(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return [];
        }

        return $data;
    }
}
