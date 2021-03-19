<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchDataflowStatus extends FetchStatus
{
    protected function getExternalComponentId(): string
    {
        return 'google-cloud-dataflow';
    }
}
