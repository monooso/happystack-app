<?php

declare(strict_types=1);

namespace App\Actions\Projects;

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
        $this->createAgency($project, $attributes);
        $this->createClient($project, $attributes);

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

            'agency.via_mail' => ['required', Rule::in(ToggleValue::all())],

            'agency.mail_route' => [
                'required_if:agency.via_email,' . ToggleValue::ENABLED,
                'email',
                'max:255',
            ],

            'client.via_mail' => ['required', Rule::in(ToggleValue::all())],

            'client.mail_route' => [
                'required_if:client.via_mail,' . ToggleValue::ENABLED,
                'email',
                'max:255',
            ],

            'client.mail_message' => [
                'required_if:client.via_mail,' . ToggleValue::ENABLED,
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
     * Create the project agency
     *
     * @param Project $project
     * @param array   $attributes
     */
    private function createAgency(Project $project, array $attributes)
    {
        $agency = $attributes['agency'];

        $project->agency()->create([
            'via_mail'   => $agency['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $agency['mail_route'] ?? '',
        ]);
    }

    /**
     * Create the project client
     *
     * @param Project $project
     * @param array   $attributes
     */
    private function createClient(Project $project, array $attributes)
    {
        $client = $attributes['client'];

        $project->client()->create([
            'via_mail'     => $client['via_mail'] === ToggleValue::ENABLED,
            'mail_route'   => $client['mail_route'] ?? '',
            'mail_message' => $client['mail_message'] ?? '',
        ]);
    }
}
