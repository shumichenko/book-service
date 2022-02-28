<?php

declare(strict_types=1);

namespace App\Exception;

use DomainException;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class InvalidRequestDataException extends DomainException implements AppExceptionInterface
{
    /**
     * @var array<string>
     */
    private array $errors = [];

    /**
     * @param array<string> $errors
     */
    public function __construct(array $errors, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
