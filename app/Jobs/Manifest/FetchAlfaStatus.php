<?php

declare(strict_types=1);

namespace App\Jobs\Manifest;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchAlfaStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'gy9xsn9gwzx1';
    }
}
