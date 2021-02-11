<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use Livewire\Redirector;

class CreateProjectForm extends Component
{
    /**
     * An array of selected components
     *
     * @var array $components
     */
    public array $components;

    /**
     * The project name
     *
     * @var string $name
     */
    public string $name;

    /**
     * The project validation rules
     *
     * @var array $rules
     */
    protected array $rules = [
        'components' => ['required', 'array', 'min:1'],
        'name'       => ['required', 'string', 'min:2', 'max:255'],
    ];

    /**
     * Initialise the component
     */
    public function mount()
    {
        $this->name = '';
        $this->components = [];
    }

    /**
     * Create a new project
     *
     * @param Request $request
     *
     * @return Redirector
     */
    public function createProject(Request $request): Redirector
    {
        $this->validate();

        $team = $request->user()->currentTeam;

        /** @var Project $project */
        $project = $team->projects()->create(['name' => $this->name]);
        $project->components()->sync($this->components);

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Response::redirectToRoute('projects.index');
    }

    /**
     * Render the component
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        $services = Service::with('components')->get();

        return view('projects.create-project-form', compact('services'));
    }
}
