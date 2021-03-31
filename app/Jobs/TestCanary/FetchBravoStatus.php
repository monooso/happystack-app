<?php

declare(strict_types=1);

namespace App\Jobs\TestCanary;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchBravoStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'js4jb7cc85bl';
    }
}
