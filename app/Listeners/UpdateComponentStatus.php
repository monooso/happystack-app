<?php

namespace App\Listeners;

use App\Events\StatusRetrieved;
use App\Events\StatusUpdated;
use App\Models\StatusUpdate as StatusUpdateModel;
use App\PlainObjects\StatusUpdate as StatusUpdateObject;
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
     * @param StatusUpdateObject $data
     *
     * @return StatusUpdateModel|null
     */
    private function getMostRecentUpdate(StatusUpdateObject $data): ?StatusUpdateModel
    {
        return StatusUpdateModel::where([
            'service'   => $data->getService(),
            'component' => $data->getComponent(),
        ])->latest('updated_at')->first();
    }

    /**
     * Has the status changed?
     *
     * @param StatusUpdateModel|null $previous
     * @param StatusUpdateObject     $new
     *
     * @return bool
     */
    private function statusHasChanged(?StatusUpdateModel $previous, StatusUpdateObject $new): bool
    {
        if ($previous === null) {
            return true;
        }

        return ($previous->status !== $new->getStatus());
    }

    /**
     * Save a new status update
     *
     * @param StatusUpdateObject $data
     *
     * @return StatusUpdateModel
     */
    private function saveNewUpdate(StatusUpdateObject $data): StatusUpdateModel
    {
        return StatusUpdateModel::create([
            'service'   => $data->getService(),
            'component' => $data->getComponent(),
            'status'    => $data->getStatus(),
        ]);
    }

    /**
     * Refresh the 'updated at' timestamp for an existing update
     *
     * @param StatusUpdateModel $model
     */
    private function refreshUpdate(StatusUpdateModel $model)
    {
        $model->touch();
    }
}
