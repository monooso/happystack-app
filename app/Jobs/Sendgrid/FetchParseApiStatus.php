<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchParseApiStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return '9mk6xltks0dj';
    }
}
