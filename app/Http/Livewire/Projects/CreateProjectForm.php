<?php

namespace App\Http\Livewire\Projects;

use App\Contracts\CreatesProjects;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateProjectForm extends Component
{
    /**
     * The selected components
     *
     * @var array $components
     */
    public array $components = [];

    /**
     * The project name
     *
     * @var string $name
     */
    public string $name = '';

    /**
     * Create a new project
     *
     * @param CreatesProjects $creator
     */
    public function create(CreatesProjects $creator)
    {
        $this->resetErrorBag();

        $creator->create(Auth::user(), [
            'components' => $this->components,
            'name'       => $this->name,
        ]);

        return redirect()->route('projects.index');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('projects.create-project-form', ['services' => Service::all()]);
    }
}
