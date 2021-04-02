<?php

declare(strict_types=1);

namespace App\Normalizers;

use App\Constants\Status;
use App\Contracts\StatusNormalizer;
use App\Exceptions\UnknownStatusException;

final class StatusPageStatus implements StatusNormalizer
{
    public static function normalize($externalStatus): string
    {
        $map = [
            'operational'          => Status::OKAY,
            'degraded_performance' => Status::WARN,
            'partial_outage'       => Status::WARN,
            'under_maintenance'    => Status::WARN,
            'major_outage'         => Status::DOWN,
        ];

        if (array_key_exists($externalStatus, $map)) {
            return $map[$externalStatus];
        }

        throw new UnknownStatusException($externalStatus);
    }
}
