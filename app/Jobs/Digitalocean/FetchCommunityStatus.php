<?php

declare(strict_types=1);

namespace App\Jobs\Digitalocean;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchCommunityStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'd5vft53jgv8n';
    }
}
