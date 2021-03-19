<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchEmailActivityStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'mdt4hw660r2x';
    }
}
