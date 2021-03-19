<?php

declare(strict_types=1);

namespace App\Jobs\Digitalocean;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchApiStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return '1082j139b5bg';
    }
}
