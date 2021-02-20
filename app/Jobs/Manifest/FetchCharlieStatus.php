<?php

declare(strict_types=1);

namespace App\Jobs\Manifest;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchCharlieStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return '3bb4g3xgzwsf';
    }
}
