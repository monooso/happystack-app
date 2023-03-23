<?php

declare(strict_types=1);

use App\Database\Migrations\SeedAwsComponentsMigration;

return new class extends SeedAwsComponentsMigration
{
    protected function getServiceKey(): string
    {
        return 'aws-ses';
    }
};
