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
        if (Str::contains($externalStatus, 'ok')) {
            return Status::OKAY;
        }

        if (Str::contains($externalStatus, 'medium')) {
            return Status::WARN;
        }

        if (Str::contains($externalStatus, 'high')) {
            return Status::DOWN;
        }

        throw new UnknownStatusException((string) $externalStatus);
    }
}
