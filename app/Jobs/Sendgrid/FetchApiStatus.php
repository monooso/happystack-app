<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchApiStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'vgl8t6wflyjt';
    }
}
