<?php

declare(strict_types=1);

namespace App\Jobs\Arcustech;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchV2PlatformUsCentralStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return '01f5vrmk9dhz';
    }
}
