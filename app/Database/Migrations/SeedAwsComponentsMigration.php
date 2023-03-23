<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use App\Constants\AwsRegion;

abstract class SeedAwsComponentsMigration extends SeedComponentsMigration
{
    /**
     * Get an array of all supported components (services and regions)
     */
    protected function getComponents(): array
    {
        $regions = AwsRegion::map();
        $prefix = $this->getServiceKey().'::';

        $keys = array_map(fn ($key) => $prefix.$key, array_keys($regions));

        return array_combine($keys, array_values($regions));
    }
}
