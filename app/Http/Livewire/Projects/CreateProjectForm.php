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

    /** @var array $agency The project agency notifications */
    public array $agency = [];

    /** @var array $client The project client notifications */
    public array $client = [];

    /**
     * Initialise the available services
     */
    public function mount()
    {
        $this->agency = $this->resetAgency($this->agency ?? []);
        $this->client = $this->resetClient($this->client ?? []);
    }

    /**
     * Get a "reset" agency array
     *
     * @param array $overrides
     *
     * @return array
     */
    private function resetAgency(array $overrides): array
    {
        return array_merge([
            'via_mail'   => ToggleValue::ENABLED,
            'mail_route' => Auth::user()->email,
        ], $overrides);
    }

    /**
     * Get a "reset" client array
     *
     * @param array $overrides
     *
     * @return array
     */
    private function resetClient(array $overrides): array
    {
        $message = (string) trans('app.client_notification', [
            'sender_name' => Auth::user()->name,
        ]);

        return array_merge([
            'via_mail'     => ToggleValue::DISABLED,
            'mail_route'   => '',
            'mail_message' => $message,
        ], $overrides);
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
            'name'       => $this->name,
            'components' => $this->components,
            'agency'     => $this->agency,
            'client'     => $this->client,
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
