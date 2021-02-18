<?php

declare(strict_types=1);

namespace App\Normalizers;

use App\Constants\Status;
use App\Contracts\StatusNormalizer;

final class StatusPageStatus implements StatusNormalizer
{
    public static function normalize(string $externalStatus): string
    {
        $map = [
            'operational'          => Status::OKAY,
            'degraded_performance' => Status::WARN,
            'partial_outage'       => Status::WARN,
            'major_outage'         => Status::DOWN,
        ];

        return array_key_exists($externalStatus, $map)
            ? $map[$externalStatus]
            : Status::UNKNOWN;
    }
}
