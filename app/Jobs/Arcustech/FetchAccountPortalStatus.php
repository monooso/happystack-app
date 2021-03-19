<?php

declare(strict_types=1);

namespace App\Jobs\Arcustech;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchAccountPortalStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'j1pn6yhmchbl';
    }
}
