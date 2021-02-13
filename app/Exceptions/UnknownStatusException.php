<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Throwable;

final class UnknownStatusException extends RuntimeException
{
    public function __construct(string $status, $code = 0, Throwable $previous = null)
    {
        $message = "Unknown status '${status}'";
        parent::__construct($message, $code, $previous);
    }
}
