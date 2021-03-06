<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Contracts\CreatesProjects;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
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
        Validator::make($attributes, [
            'name'           => ['required', 'string', 'min:1', 'max:255'],
            'components'     => ['required', 'array', 'min:1'],
            'components.*'   => ['exists:components,id'],
            'channels.email' => ['required', 'email', 'max:255'],
            'notifyClient'   => ['required', 'boolean'],
            'clientEmail'    => ['required_if:notifyClient,true', 'email', 'max:255'],
            'clientMessage'  => ['required_if:notifyClient,true', 'string', 'min:1', 'max:60000'],
        ])->validate();

        /** @var Team $team */
        $team = $user->currentTeam;

        /** @var Project $project */
        $project = $team->projects()->create([
            'name'               => $attributes['name'],
            'notification_email' => $attributes['channels']['email'],
            'notify_client'      => $attributes['notifyClient'],
            'client_email'       => $attributes['clientEmail'],
            'client_message'     => $attributes['clientMessage'],
        ]);

        $project->components()->sync($attributes['components']);

        return $project;
    }
}
