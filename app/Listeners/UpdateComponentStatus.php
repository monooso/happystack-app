<?php

namespace App\Listeners;

use App\Events\StatusRetrieved;
use App\Events\StatusUpdated;
use App\Models\StatusUpdate;
use App\PlainObjects\ComponentStatus;
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
        $eventUpdate = $event->statusUpdate;

        $previousUpdate = $this->getMostRecentUpdate($eventUpdate);

        if ($this->statusHasChanged($previousUpdate, $eventUpdate)) {
            $update = $this->saveNewUpdate($eventUpdate);
            StatusUpdated::dispatch($update);
        } else {
            $this->refreshUpdate($previousUpdate);
        }
    }

    /**
     * Get the most recent update from the database
     *
     * @param ComponentStatus $data
     *
     * @return StatusUpdate|null
     */
    private function getMostRecentUpdate(ComponentStatus $data): ?StatusUpdate
    {
        return StatusUpdate::where([
            'service'   => $data->getService(),
            'component' => $data->getComponent(),
        ])->latest('updated_at')->first();
    }

    /**
     * Has the status changed?
     *
     * @param StatusUpdate|null $previous
     * @param ComponentStatus   $new
     *
     * @return bool
     */
    private function statusHasChanged(?StatusUpdate $previous, ComponentStatus $new): bool
    {
        if ($previous === null) {
            return true;
        }

        return ($previous->status !== $new->getStatus());
    }

    /**
     * Save a new status update
     *
     * @param ComponentStatus $data
     *
     * @return StatusUpdate
     */
    private function saveNewUpdate(ComponentStatus $data): StatusUpdate
    {
        return StatusUpdate::create([
            'service'   => $data->getService(),
            'component' => $data->getComponent(),
            'status'    => $data->getStatus(),
        ]);
    }

    /**
     * Refresh the 'updated at' timestamp for an existing update
     *
     * @param StatusUpdate $model
     */
    private function refreshUpdate(StatusUpdate $model)
    {
        $model->touch();
    }
}
