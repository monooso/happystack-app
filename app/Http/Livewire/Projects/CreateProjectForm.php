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
    /** @var string $name The project name */
    public string $name = '';

    /** @var array $channels The notification channels */
    public array $channels = [];

    /** @var array $components The components to monitor */
    public array $components = [];

    /** @var bool $notifyClient Whether we should notify the client */
    public bool $notifyClient = false;

    /** @var string $clientEmail The client's email address */
    public string $clientEmail = '';

    /** @var string $clientMessage The message we will send to the client */
    public string $clientMessage = '';

    /**
     * Initialise the available services
     */
    public function mount()
    {
        $this->clientMessage = $this->clientMessage ?: (string) trans(
            'app.client_notification',
            ['sender_name' => Auth::user()->name]
        );

        $this->channels = $this->resetChannels($this->channels ?? []);
    }

    private function resetChannels(array $overrides): array
    {
        return array_merge(['email' => Auth::user()->email], $overrides);
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
        $selectedComponentIds = array_values($this->components);

        return $service->components->filter(
            fn ($component) => in_array($component->id, $selectedComponentIds)
        );
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
            'name'          => $this->name,
            'components'    => $this->components,
            'channels'      => $this->channels,
            'notifyClient'  => $this->notifyClient,
            'clientEmail'   => $this->clientEmail,
            'clientMessage' => $this->clientMessage,
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('projects.create-project-form', [
            'services' => Service::with('components')->get()->sortBy('name'),
        ]);
    }
}
