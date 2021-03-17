<?php

declare(strict_types=1);

namespace App\Jobs\Arcustech;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchV2PlatformEuNl extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'l3p9kx6f1h27';
    }
}
