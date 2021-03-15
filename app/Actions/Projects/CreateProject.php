<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Constants\NotificationChannel;
use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class CreateProject implements CreatesProjects
{
    /**
     * Create a new project for the given user
     *
     * @param User  $user
     * @param array $attributes
     *
     * @return Project
     * @throws ValidationException
     */
    public function create(User $user, array $attributes): Project
    {
        $attributes = $this->validate($attributes);

        /** @var Team $team */
        $team = $user->currentTeam;

        /** @var Project $project */
        $project = $team->projects()->create(['name' => $attributes['name']]);

        $this->createComponents($project, $attributes);
        $this->createAgencyChannels($project, $attributes);
        $this->createClientChannels($project, $attributes);

        return $project;
    }

    /**
     * Validate the project attributes
     *
     * @param array $attributes
     *
     * @return array
     * @throws ValidationException
     */
    private function validate(array $attributes): array
    {
        $rules = [
            'name'         => ['required', 'string', 'min:1', 'max:255'],
            'components'   => ['required', 'array', 'min:1'],
            'components.*' => ['exists:components,id'],

            'agencyChannels.email.enabled' => [
                'required',
                Rule::in(ToggleValue::all()),
            ],
            'agencyChannels.email.route' => [
                'required_if:agencyChannels.email.enabled,' . ToggleValue::ENABLED,
                'email',
                'max:255',
            ],

            'clientChannels.email.enabled' => [
                'required',
                Rule::in(ToggleValue::all()),
            ],
            'clientChannels.email.route' => [
                'required_if:clientChannels.email.enabled,' . ToggleValue::ENABLED,
                'email',
                'max:255',
            ],
            'clientChannels.email.message' => [
                'required_if:clientChannels.email.enabled,' . ToggleValue::ENABLED,
                'string',
                'min:1',
                'max:60000',
            ],
        ];

        $messages = Lang::get('validation.custom.createProject');
        $flattened = Arr::flattenAssoc($messages);

        return Validator::make($attributes, $rules, $flattened)->validate();
    }

    /**
     * Associate the selected components with the project
     *
     * @param Project $project
     * @param array   $attributes
     */
    private function createComponents(Project $project, array $attributes)
    {
        $project->components()->sync($attributes['components']);
    }

    /**
     * Create the project agency notification channels
     *
     * @param Project $project
     * @param array   $attributes
     */
    private function createAgencyChannels(Project $project, array $attributes)
    {
        $agencyEmail = $attributes['agencyChannels']['email'];

        if ($agencyEmail['enabled'] === ToggleValue::ENABLED) {
            $project->agencyChannels()->create([
                'type'  => NotificationChannel::MAIL,
                'route' => $agencyEmail['route'],
            ]);
        }
    }

    /**
     * Create the project client notification channels
     *
     * @param Project $project
     * @param array   $attributes
     */
    private function createClientChannels(Project $project, array $attributes)
    {
        $clientEmail = $attributes['clientChannels']['email'];

        if ($clientEmail['enabled'] === ToggleValue::ENABLED) {
            $project->clientChannels()->create([
                'type'    => NotificationChannel::MAIL,
                'route'   => $clientEmail['route'],
                'message' => $clientEmail['message'],
            ]);
        }
    }
}
