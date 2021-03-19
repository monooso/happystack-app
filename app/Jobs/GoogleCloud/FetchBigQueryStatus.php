<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchBigQueryStatus extends FetchStatus
{
    protected function getExternalComponentId(): string
    {
        return 'google-bigquery';
    }
}
