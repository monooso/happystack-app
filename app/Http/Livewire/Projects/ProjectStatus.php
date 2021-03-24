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
     * Render the component
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('projects.project-status');
    }

    /**
     * Refresh the project
     */
    public function refresh()
    {
        $this->project->refresh();
    }
}
