<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchOperationsStatus extends FetchStatus
{
    protected function getExternalComponentId(): string
    {
        return 'operations';
    }
}
