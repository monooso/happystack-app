<?php

declare(strict_types=1);

namespace App\Jobs\Manifest;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchBravoStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'js4jb7cc85bl';
    }
}
