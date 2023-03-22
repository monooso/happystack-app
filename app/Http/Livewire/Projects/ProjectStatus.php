<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Contracts\DeletesProjects;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Redirector;

final class ProjectStatus extends Component
{
    public bool $confirmingProjectDeletion = false;

    public Project $project;

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        return view('projects.project-status');
    }

    /**
     * Refresh the project
     */
    public function refresh(): void
    {
        $this->project->refresh();
    }

    /**
     * Delete the project
     */
    public function deleteProject(DeletesProjects $deleter): Redirector
    {
        $deleter->delete(Auth::user(), $this->project);

        return redirect()->route('dashboard');
    }
}
