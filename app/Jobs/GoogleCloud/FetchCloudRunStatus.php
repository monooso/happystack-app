<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchCloudRunStatus extends FetchStatus
{
    protected function getExternalComponentId(): string
    {
        return 'cloud-run';
    }
}
