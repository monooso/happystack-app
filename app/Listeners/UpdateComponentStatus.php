<?php

namespace App\Listeners;

use App\Events\StatusRetrieved;
use App\Events\StatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateComponentStatus implements ShouldQueue
{
    /**
     * Handle the event
     *
     * @param  StatusRetrieved  $event
     *
     * @return void
     */
    public function handle(StatusRetrieved $event): void
    {
        $component = $event->component;
        $newStatus = $event->status;

        if ($newStatus !== $component->current_status) {
            $component->updateStatus($newStatus);
            StatusUpdated::dispatch($component);
        } else {
            // Touch the updated_at timestamp of the most recent status update
            $component->statusUpdates()->latest()->touch();
        }
    }
}
