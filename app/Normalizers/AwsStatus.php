<?php

declare(strict_types=1);

namespace App\Normalizers;

use App\Constants\Status;
use App\Contracts\StatusNormalizer;
use App\Exceptions\UnknownStatusException;

final class AwsStatus implements StatusNormalizer
{
    public static function normalize($externalStatus): string
    {
        $map = [1 => Status::OKAY, 2 => Status::WARN, 3 => Status::DOWN];

        if (array_key_exists($externalStatus, $map)) {
            return $map[$externalStatus];
        }

        throw new UnknownStatusException((string) $externalStatus);
    }
}
