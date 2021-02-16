<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Contracts\CreatesProjects;
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
            'components' => ['required', 'array', 'min:1'],
            'name'       => ['required', 'string', 'min:1', 'max:255'],
        ])->validate();

        /** @var Team $team */
        $team = $user->currentTeam;

        /** @var Project $project */
        $project = $team->projects()->create(['name' => $attributes['name']]);
        $project->components()->sync($attributes['components']);

        return $project;
    }
}
