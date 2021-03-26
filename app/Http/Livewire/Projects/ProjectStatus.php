<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Contracts\DeletesProjects;
use App\Models\Project;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Redirector;

final class ProjectStatus extends Component
{
    public bool $confirmingProjectDeletion = false;

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

    /**
     * Delete the project
     *
     * @param DeletesProjects $deleter
     *
     * @return Redirector
     */
    public function deleteProject(DeletesProjects $deleter): Redirector
    {
        $deleter->delete(Auth::user(), $this->project);

        return redirect()->route('dashboard');
    }
}
