<?php

declare(strict_types=1);

namespace App\Jobs\Digitalocean;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchKubernetesStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'y5mb12th36zh';
    }
}
