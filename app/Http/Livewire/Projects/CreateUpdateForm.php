<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Contracts\UpdatesProjects;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Redirector;

final class CreateUpdateForm extends Component
{
    /** @var array $agency The project agency notifications */
    public array $agency = [];

    /** @var array $client The project client notifications */
    public array $client = [];

    /** @var array The components to monitor */
    public array $components = [];

    /** @var string $name The project name */
    public string $name = '';

    /** @var Project The project */
    public Project $project;

    /**
     * Initialise the available services
     *
     * @param Project $project
     */
    public function mount(Project $project)
    {
        $this->project = $project;

        $this->name = $project->name ?? '';
        $this->components = $this->resetComponents();
        $this->agency = $this->resetAgency();
        $this->client = $this->resetClient();
    }

    /**
     * Reset the project components
     *
     * @return array
     */
    private function resetComponents(): array
    {
        return $this->project->components->pluck('id')->all();
    }

    /**
     * Reset the project agency
     *
     * @return array
     */
    private function resetAgency(): array
    {
        $agency = $this->project->agency;

        return [
            'via_mail'   => $agency?->via_mail === true ? ToggleValue::ENABLED : ToggleValue::DISABLED,
            'mail_route' => $agency?->mail_route ?? Auth::user()->email,
        ];
    }

    /**
     * Reset the project client
     *
     * @return array
     */
    private function resetClient(): array
    {
        $client = $this->project->client;

        $message = (string) trans('app.client_notification', [
            'sender_name' => Auth::user()->name,
        ]);

        return [
            'via_mail'     => $client?->via_mail === true ? ToggleValue::ENABLED : ToggleValue::DISABLED,
            'mail_route'   => $client?->mail_route ?? '',
            'mail_message' => $client?->mail_message ?? $message,
        ];
    }

    /**
     * Computed property `notifyClient`
     *
     * @return bool
     */
    public function getNotifyClientProperty(): bool
    {
        return isset($this->client['via_mail'])
            && $this->client['via_mail'] === ToggleValue::ENABLED;
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
     * Creates or updates a project
     *
     * @param CreatesProjects $creator
     * @param UpdatesProjects $updater
     *
     * @return Redirector
     * @throws ValidationException
     */
    public function save(CreatesProjects $creator, UpdatesProjects $updater): Redirector
    {
        $this->resetErrorBag();

        $user = Auth::user();

        $payload = [
            'name'       => $this->name,
            'components' => $this->components,
            'agency'     => $this->agency,
            'client'     => $this->client,
        ];

        $this->project->exists
            ? $updater->update($user, $this->project, $payload)
            : $creator->create($user, $payload);

        return redirect()->route('dashboard');
    }

    /**
     * Render the component
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('projects.create-update-form', [
            'services' => Service::with('components')->get()->sortBy('name'),
        ]);
    }
}
