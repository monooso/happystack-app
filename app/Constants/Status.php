<?php

declare(strict_types=1);

namespace App\Constants;

abstract class Status
{
    public const DOWN = 'down';

    public const OKAY = 'okay';

    public const UNKNOWN = 'unknown';

    public const WARN = 'warn';

    /**
     * Get an array of all statuses
     */
    public static function all(): array
    {
        return [self::OKAY, self::WARN, self::DOWN, self::UNKNOWN];
    }

    /**
     * Get an array of all known statuses
     */
    public static function known(): array
    {
        return [self::OKAY, self::WARN, self::DOWN];
    }
}
