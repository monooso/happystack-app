<?php

declare(strict_types=1);

namespace App\Contracts;

interface StatusNormalizer
{
    /**
     * Convert an external status into an internal status
     *
     * @param string $externalStatus
     *
     * @return string
     */
    public static function normalize(string $externalStatus): string;
}
