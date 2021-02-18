<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Throwable;

final class UnknownComponentException extends RuntimeException
{
    public function __construct(string $componentId, $code = 0, Throwable $previous = null)
    {
        $message = "Unknown component '${componentId}'";
        parent::__construct($message, $code, $previous);
    }
}
