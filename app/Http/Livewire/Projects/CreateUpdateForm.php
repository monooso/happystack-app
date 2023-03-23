<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Contracts\UpdatesProjects;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Redirector;

final class CreateUpdateForm extends Component
{
    // The project agency notifications
    public array $agency = [];

    // The project client notifications
    public array $client = [];

    // The components to monitor
    public array $components = [];

    // $name The project name
    public string $name = '';

    /** @var Project The project */
    public Project $project;

    /**
     * Initialise the available services
     */
    public function mount(Project $project): void
    {
        $this->project = $project;

        $this->name = $project->name ?? '';
        $this->components = $this->resetComponents();
        $this->agency = $this->resetAgency();
        $this->client = $this->resetClient();
    }

    /**
     * Reset the project components
     */
    private function resetComponents(): array
    {
        return $this->project->components->pluck('id')->all();
    }

    /**
     * Reset the project agency
     */
    private function resetAgency(): array
    {
        $agency = $this->project->agency;

        if (is_null($agency)) {
            return [
                'mail_route' => user()->email,
                'via_mail' => ToggleValue::ENABLED,
            ];
        }

        return [
            'mail_route' => $agency->mail_route,
            'via_mail' => $agency->via_mail === true ? ToggleValue::ENABLED : ToggleValue::DISABLED,
        ];
    }

    /**
     * Reset the project client
     */
    private function resetClient(): array
    {
        $client = $this->project->client;

        $message = (string) trans('app.client_notification', [
            'sender_name' => user()->name,
        ]);

        return [
            'via_mail' => $client?->via_mail === true ? ToggleValue::ENABLED : ToggleValue::DISABLED,
            'mail_route' => $client?->mail_route ?? '',
            'mail_message' => $client?->mail_message ?? $message,
        ];
    }

    /**
     * Computed property `notifyClient`
     */
    public function getNotifyClientProperty(): bool
    {
        return isset($this->client['via_mail'])
            && $this->client['via_mail'] === ToggleValue::ENABLED;
    }

    /**
     * Return the selected components which belong to the given service
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
     * @throws ValidationException
     */
    public function save(CreatesProjects $creator, UpdatesProjects $updater): Redirector
    {
        $this->resetErrorBag();

        $user = Auth::user();

        $payload = [
            'name' => $this->name,
            'components' => $this->components,
            'agency' => $this->agency,
            'client' => $this->client,
        ];

        $this->project->exists
            ? $updater->update($user, $this->project, $payload)
            : $creator->create($user, $payload);

        return redirect()->route('dashboard');
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        return view('projects.create-update-form', [
            'services' => Service::with('components')->get()->sortBy('name'),
        ]);
    }
}
