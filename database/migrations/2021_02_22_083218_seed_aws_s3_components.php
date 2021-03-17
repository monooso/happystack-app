<?php

declare(strict_types=1);

use App\Database\Migrations\SeedAwsComponentsMigration;

final class SeedAwsS3Components extends SeedAwsComponentsMigration
{
    protected function getServiceKey(): string
    {
        return 'aws-s3';
    }
}
