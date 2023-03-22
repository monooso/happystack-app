<?php

declare(strict_types=1);

namespace App\Constants;

final class ServiceVisibility
{
    public const PUBLIC = 'public';

    public const RESTRICTED = 'restricted';

    public static function all(): array
    {
        return [self::PUBLIC, self::RESTRICTED];
    }
}
