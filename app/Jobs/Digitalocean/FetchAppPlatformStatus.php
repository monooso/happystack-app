<?php

declare(strict_types=1);

namespace App\Jobs\Digitalocean;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchAppPlatformStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'vtmbhmvd5dqv';
    }
}
