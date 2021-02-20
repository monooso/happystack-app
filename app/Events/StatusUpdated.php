<?php

namespace App\Events;

use App\Models\Component;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public Component $component;

    /**
     * Constructor
     *
     * @param Component $component
     */
    public function __construct(Component $component)
    {
        $this->component = $component;
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
        $componentHandle = $this->component->handle;
        $serviceHandle = $this->component->service->handle;

        $channelName = "${serviceHandle}.${componentHandle}";

        return new Channel($channelName);
    }

    /**
     * Broadcast an array of data
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return ['component' => $this->component];
    }
}
