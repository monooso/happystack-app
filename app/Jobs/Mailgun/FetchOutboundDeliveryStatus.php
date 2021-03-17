<?php

declare(strict_types=1);

namespace App\Jobs\Mailgun;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchOutboundDeliveryStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return '3x8cldj79lmg';
    }
}
