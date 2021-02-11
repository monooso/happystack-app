<?php

namespace App\Http\Livewire;

use App\Constants\Status;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Livewire\Component;

class ComponentStatus extends Component
{
    /**
     * The service key. For example, 'mailgun'
     *
     * @var string
     */
    public string $service;

    /**
     * The service component status
     *
     * @var string
     */
    public string $status = Status::UNKNOWN;

    /**
     * The service component key. For example, 'api'
     *
     * @var string
     */
    public string $component;

    /**
     * Subscribe to broadcast events
     *
     * @return string[]
     */
    public function getListeners(): array
    {
        $channelName = $this->service . '.' . $this->component;

        return ["echo:${channelName},StatusUpdated" => 'updateStatus'];
    }

    /**
     * Render the component
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('livewire.component-status');
    }

    /**
     * Update the component status in response to an event
     *
     * @param array $event
     */
    public function updateStatus(array $event)
    {
        $this->status = Arr::get($event, 'status', Status::UNKNOWN);
    }
}
