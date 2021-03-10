<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Models\Service;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class CreateProjectForm extends Component
{
    /** @var string $name The project name */
    public string $name = '';

    /** @var array The components to monitor */
    public array $components = [];

    /** @var array $agencyChannels The agency notification channels */
    public array $agencyChannels = [];

    /** @var array $clientChannels The client notification channels */
    public array $clientChannels = [];

    /**
     * Initialise the available services
     */
    public function mount()
    {
        $this->agencyChannels = $this->resetAgencyChannels($this->agencyChannels ?? []);
        $this->clientChannels = $this->resetClientChannels($this->clientChannels ?? []);
    }

    /**
     * Get a "reset" agency channels array
     *
     * @param array $overrides
     *
     * @return array
     */
    private function resetAgencyChannels(array $overrides): array
    {
        return array_merge([
            'email' => [
                'enabled' => ToggleValue::ENABLED,
                'route'   => Auth::user()->email,
            ],
        ], $overrides);
    }

    /**
     * Get a "reset" client channels array
     *
     * @param array $overrides
     *
     * @return array
     */
    private function resetClientChannels(array $overrides): array
    {
        $message = (string) trans('app.client_notification', [
            'sender_name' => Auth::user()->name,
        ]);

        return array_merge([
            'email' => [
                'enabled' => ToggleValue::DISABLED,
                'route'   => '',
                'message' => $message,
            ],
        ], $overrides);
    }

    /**
     * Computed property `clientEmailNotificationsEnabled`
     *
     * @return bool
     */
    public function getClientEmailNotificationsEnabledProperty(): bool
    {
        return isset($this->clientChannels['email']['enabled'])
            && $this->clientChannels['email']['enabled'] === ToggleValue::ENABLED;
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
            'name'           => $this->name,
            'components'     => $this->components,
            'agencyChannels' => $this->agencyChannels,
            'clientChannels' => $this->clientChannels,
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Render the component
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('projects.create-project-form', [
            'services' => Service::with('components')->get()->sortBy('name'),
        ]);
    }
}
