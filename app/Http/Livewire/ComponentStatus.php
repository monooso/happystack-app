<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Constants\Status;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Livewire\Component;

final class ComponentStatus extends Component
{
    public string $key;
    public string $status;

    public function mount(string $key, string $status = Status::UNKNOWN)
    {
        $this->key = $key;
        $this->status = $status;
    }

    /**
     * Subscribe to broadcast events
     *
     * @return string[]
     */
    public function getListeners(): array
    {
        $channelName = 'component-' . $this->key;

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
