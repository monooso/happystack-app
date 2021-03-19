<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchApiV2Status extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'q6bv40cywctx';
    }
}
