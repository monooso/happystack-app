<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Contracts\CreatesProjects;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class CreateProjectForm extends Component
{
    /**
     * The email to which we will send client notifications
     *
     * @var string
     */
    public string $clientNotificationEmail = '';

    /**
     * The client name, used in notification emails
     *
     * @var string
     */
    public string $clientNotificationName = '';

    /**
     * The email to which we will send notifications
     *
     * @var string
     */
    public string $notificationEmail = '';

    /**
     * Whether we should notify the client of any issues
     *
     * @var bool
     */
    public bool $notifyClient = false;

    /**
     * The components to monitor
     *
     * @var array
     */
    public array $projectComponents = [];

    /**
     * The project name
     *
     * @var string
     */
    public string $projectName = '';

    /**
     * The available services
     *
     * @var Collection $services
     */
    public Collection $services;

    /**
     * Initialise the available services
     */
    public function mount()
    {
        $this->services = Service::with('components')->get()->sortBy('name');
    }

    /**
     * Return the selected components which belong to the given service
     *
     * @param Service $service
     *
     * @return Collection
     */
    public function selectedServiceComponents(Service $service): Collection
    {
        $selectedComponentIds = array_values($this->projectComponents);

        return $service->components->filter(fn ($component) => in_array($component->id, $selectedComponentIds));
    }

    /**
     * Create a new project
     *
     * @param CreatesProjects $creator
     *
     * @return RedirectResponse
     */
    public function create(CreatesProjects $creator)
    {
        $this->resetErrorBag();

        $creator->create(Auth::user(), [
            'clientNotificationEmail' => $this->clientNotificationEmail,
            'clientNotificationName'  => $this->clientNotificationName,
            'notificationEmail'       => $this->notificationEmail,
            'notifyClient'            => $this->notifyClient,
            'projectComponents'       => $this->projectComponents,
            'projectName'             => $this->projectName,
        ]);

        return redirect()->route('projects.index');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('projects.create-project-form', ['services' => $this->services]);
    }
}
