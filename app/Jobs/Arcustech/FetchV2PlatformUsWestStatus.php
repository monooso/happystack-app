<?php

declare(strict_types=1);

namespace App\Jobs\Arcustech;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchV2PlatformUsWestStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return '84fdqr0d0jcd';
    }
}
