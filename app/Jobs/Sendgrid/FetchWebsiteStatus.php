<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchWebsiteStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return '4kpfnh9zn3cq';
    }
}
