<?php

declare(strict_types=1);

namespace App\Jobs\Arcustech;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchCustomerSupportStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return '2xhkp7ldgm2f';
    }
}
