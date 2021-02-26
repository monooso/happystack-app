<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Contracts\CreatesProjects;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

final class CreateProject implements CreatesProjects
{
    /**
     * Create a new project for the given user
     *
     * @param User  $user
     * @param array $input
     *
     * @return Project
     */
    public function create(User $user, array $attributes): Project
    {
        Validator::make($attributes, [
            'projectName'             => ['required', 'string', 'min:1', 'max:255'],
            'projectServices'         => ['required', 'array', 'min:1'],
            'notificationEmail'       => ['required', 'email', 'max:255'],
            'notifyClient'            => ['required', 'boolean'],
            'clientNotificationEmail' => ['required_if:notifyClient,true', 'email', 'max:255'],
            'clientNotificationName'  => ['required_if:notifyClient,true', 'string', 'min:1', 'max:255'],
        ])->validate();

        // Get all of the components for each of the selected services
        $components = Component::whereIn('service_id', $attributes['projectServices'])->pluck('id');

        /** @var Team $team */
        $team = $user->currentTeam;

        /** @var Project $project */
        $project = $team->projects()->create(['name' => $attributes['projectName']]);
        $project->components()->sync($components);

        return $project;
    }
}
