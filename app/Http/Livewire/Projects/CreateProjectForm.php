<?php

declare(strict_types=1);

namespace App\Http\Livewire\Projects;

use App\Contracts\CreatesProjects;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class CreateProjectForm extends Component
{
    /**
     * The project name
     *
     * @var string
     */
    public string $projectName = '';

    /**
     * The services to monitor
     *
     * @var array
     */
    public array $projectServices = [];

    /**
     * The email to which we will send notifications
     *
     * @var string
     */
    public string $notificationEmail = '';

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
     * Whether we should notify the client of any issues
     *
     * @var bool
     */
    public bool $notifyClient = false;

    /**
     * Show or hide the client notification email preview
     *
     * @var bool
     */
    public bool $showClientNotificationEmailPreview = false;

    /**
     * Create a new project
     *
     * @param CreatesProjects $creator
     */
    public function create(CreatesProjects $creator)
    {
        $this->resetErrorBag();

        $creator->create(Auth::user(), [
            'projectName'             => $this->projectName,
            'projectServices'         => $this->projectServices,
            'notificationEmail'       => $this->notificationEmail,
            'notifyClient'            => $this->notifyClient,
            'clientNotificationEmail' => $this->clientNotificationEmail,
            'clientNotificationName'  => $this->clientNotificationName,
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
