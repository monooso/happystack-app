<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchApiV3Status extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 's0rtr2pvxgzq';
    }
}
