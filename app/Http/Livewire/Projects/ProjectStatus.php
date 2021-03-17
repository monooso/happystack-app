<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Models\Project;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class ProjectStatus extends Component
{
    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Subscribe to component status changes
     *
     * @return string[]
     */
    public function getListeners(): array
    {
        $listeners = [];

        foreach ($this->project->components as $component) {
            $channelName = 'component-' . $component->id;
            $listeners["echo:${channelName},StatusUpdated"] = 'updateStatus';
        }

        return $listeners;
    }

    /**
     * Render the component
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('projects.project-status');
    }

    /**
     * Update the project status in response to an event
     *
     * @param array $event
     */
    public function updateStatus(array $event)
    {
        $this->project->refresh();
    }
}
