<?php

declare(strict_types=1);

namespace App\Jobs\Digitalocean;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchContainerRegistryStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'bcwz10mdvf0r';
    }
}
