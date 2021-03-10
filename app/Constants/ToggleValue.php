<?php

declare(strict_types=1);

namespace App\Constants;

abstract class ToggleValue
{
    public const ENABLED = 'yes';
    public const DISABLED = 'no';

    public static function all(): array
    {
        return [self::ENABLED, self::DISABLED];
    }
}
