<?php

use App\Database\Migrations\SeedAwsComponentsMigration;

class SeedAwsSesComponents extends SeedAwsComponentsMigration
{
    protected function getServiceKey(): string
    {
        return 'aws-ses';
    }
}
