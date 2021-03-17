<?php

use App\Database\Migrations\SeedAwsComponentsMigration;

class SeedAwsS3Components extends SeedAwsComponentsMigration
{
    protected function getServiceKey(): string
    {
        return 'aws-s3';
    }
}
