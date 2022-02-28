<?php

declare(strict_types=1);

namespace App\Repository\Exception;

use RuntimeException;

class EntityNotFoundException extends RuntimeException implements RepositoryExceptionInterface
{
}
