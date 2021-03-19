<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchCloudBigtableStatus extends FetchStatus
{
    protected function getExternalComponentId(): string
    {
        return 'google-cloud-bigtable';
    }
}
