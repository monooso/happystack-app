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
            'projectName'             => ['required', 'string', 'min:1', 'max:255'],
            'projectComponents'       => ['required', 'array', 'min:1'],
            'projectComponents.*'     => ['exists:components,id'],
            'notificationEmail'       => ['required', 'email', 'max:255'],
            'notifyClient'            => ['required', 'boolean'],
            'clientNotificationEmail' => ['required_if:notifyClient,true', 'email', 'max:255'],
            'clientNotificationName'  => ['required_if:notifyClient,true', 'string', 'min:1', 'max:255'],
        ])->validate();

        /** @var Team $team */
        $team = $user->currentTeam;

        /** @var Project $project */
        $project = $team->projects()->create([
            'name'                      => $attributes['projectName'],
            'notification_email'        => $attributes['notificationEmail'],
            'should_notify_client'      => $attributes['notifyClient'],
            'client_notification_name'  => $attributes['clientNotificationName'],
            'client_notification_email' => $attributes['clientNotificationEmail'],
        ]);

        $project->components()->sync($attributes['projectComponents']);

        return $project;
    }
}
