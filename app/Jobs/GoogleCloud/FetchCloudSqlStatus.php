<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchCloudSqlStatus extends FetchStatus
{
    protected function getComponentId(): string
    {
        return 'google-cloud-sql';
    }
}
