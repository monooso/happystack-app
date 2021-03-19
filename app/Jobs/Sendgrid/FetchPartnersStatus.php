<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchPartnersStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'ylw5fnzv1r15';
    }
}
