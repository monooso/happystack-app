<?php

declare(strict_types=1);

namespace App\Jobs\Manifest;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchCharlieStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return '3bb4g3xgzwsf';
    }
}
