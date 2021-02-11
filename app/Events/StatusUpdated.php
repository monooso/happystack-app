<?php

namespace App\Events;

use App\Models\StatusUpdate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public StatusUpdate $statusUpdate;

    /**
     * Constructor
     *
     * @param StatusUpdate $statusUpdate
     */
    public function __construct(StatusUpdate $statusUpdate)
    {
        $this->statusUpdate = $statusUpdate;
    }

    /**
     * Broadcast the event on a channel named after the service and component
     *
     * For example, `mailgun.api`
     *
     * @return string
     */
    public function broadcastOn(): string
    {
        $name = $this->statusUpdate->service . '.' . $this->statusUpdate->component;

        return new Channel($name);
    }

    /**
     * Broadcast an array of data
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'component' => $this->statusUpdate->component,
            'service'   => $this->statusUpdate->service,
            'status'    => $this->statusUpdate->status,
        ];
    }
}
