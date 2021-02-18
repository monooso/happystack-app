<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Exceptions\UnknownStatusException;

interface StatusNormalizer
{
    /**
     * Convert an external status into an internal status
     *
     * @param string $externalStatus
     *
     * @return string
     *
     * @throws UnknownStatusException
     */
    public static function normalize(string $externalStatus): string;
}
