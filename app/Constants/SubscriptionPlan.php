<?php

declare(strict_types=1);

namespace App\Constants;

final class SubscriptionPlan
{
    public const SMALL = 'Small';

    public const MEDIUM = 'Medium';

    public const LARGE = 'Large';

    public static function all(): array
    {
        return [self::SMALL, self::MEDIUM, self::LARGE];
    }
}
