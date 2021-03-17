<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchMemorystoreStatus extends FetchStatus
{
    protected function getComponentId(): string
    {
        return 'cloud-memorystore';
    }
}
