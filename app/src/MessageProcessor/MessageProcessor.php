<?php

declare(strict_types = 1);

namespace App\MessageProcessor;

use App\Exception\InvalidRequestDataException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use function array_shift;

class MessageProcessor
{
    use HandleTrait;

    private ValidatorInterface $validator;

    public function __construct(MessageBusInterface $messageBus, ValidatorInterface $validator)
    {
        $this->messageBus = $messageBus;
        $this->validator = $validator;
    }

    public function command(CommandInterface $command)
    {
        return $this->dispatch($command);
    }

    public function query(QueryInterface $query)
    {
        return $this->dispatch($query);
    }

    private function dispatch(MessageInterface $message)
    {
        $this->validate($message);

        try {
            return $this->handle($message);
        } catch (HandlerFailedException $exception) {
            throw $this->getActualException($exception);
        }
    }

    private function validate(MessageInterface $message): void
    {
        $violations = $this->validator->validate($message);
        if (!$violations->count()) {
            return;
        }

        $errors = [];
        foreach ($violations as $constraintViolation) {
            $errors[$constraintViolation->getPropertyPath()] = (string) $constraintViolation->getMessage();
        }
        throw new InvalidRequestDataException($errors);
    }

    private function getActualException(HandlerFailedException $exception): Throwable
    {
        $exceptions = $exception->getNestedExceptions();
        if (1 === count($exceptions)) {
            return array_shift($exceptions);
        }

        return $exception;
    }
}
