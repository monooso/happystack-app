<?php

declare(strict_types=1);

namespace App\Jobs\Mailgun;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchInboxPlacementStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'tnhtt5q8vklm';
    }
}
