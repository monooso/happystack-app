<?php

declare(strict_types=1);

use App\Database\Migrations\SeedAwsComponentsMigration;

final class SeedAwsSnsComponents extends SeedAwsComponentsMigration
{
    protected function getServiceKey(): string
    {
        return 'aws-sns';
    }
}
