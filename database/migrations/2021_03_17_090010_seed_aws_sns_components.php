<?php

use App\Database\Migrations\SeedAwsComponentsMigration;

class SeedAwsSnsComponents extends SeedAwsComponentsMigration
{
    protected function getServiceKey(): string
    {
        return 'aws-sns';
    }
}
