<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatusChanged;
use App\Events\StatusFetched;

final class UpdateComponentStatus
{
    public function handle(StatusFetched $event): void
    {
        $component = $event->component;
        $newStatus = $event->status;
        $oldStatus = $component->status;

        $component->updateStatus($newStatus);

        if ($newStatus !== $oldStatus) {
            StatusChanged::dispatch($component);
        }
    }
}
