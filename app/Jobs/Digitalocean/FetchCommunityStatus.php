<?php

declare(strict_types=1);

namespace App\Jobs\Digitalocean;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchCommunityStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'd5vft53jgv8n';
    }
}
