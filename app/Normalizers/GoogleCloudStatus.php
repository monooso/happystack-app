<?php

declare(strict_types=1);

namespace App\Normalizers;

use App\Constants\Status;
use App\Contracts\StatusNormalizer;
use App\Exceptions\UnknownStatusException;
use Illuminate\Support\Str;

final class GoogleCloudStatus implements StatusNormalizer
{
    public static function normalize($externalStatus): string
    {
        $map = [
            'available'   => Status::OKAY,
            'information' => Status::OKAY,
            'disruption'  => Status::WARN,
            'outage'      => Status::DOWN,
        ];

        $status = collect($map)->first(
            fn ($status, $key) => Str::contains($externalStatus, $key)
        );

        return $status ?? throw new UnknownStatusException((string) $externalStatus);
    }
}
