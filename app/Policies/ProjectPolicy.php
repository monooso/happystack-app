<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create a new project
     *
     * @return bool
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the project
     *
     * @param User    $user
     * @param Project $project
     *
     * @return bool
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->belongsToTeam($project->team);
    }

    /**
     * Determine whether the user can update the project
     *
     * @param User    $user
     * @param Project $project
     *
     * @return bool
     */
    public function update(User $user, Project $project): bool
    {
        return $user->belongsToTeam($project->team);
    }

    /**
     * Determine whether the user can view the project
     *
     * @param User    $user
     * @param Project $project
     *
     * @return bool
     */
    public function view(User $user, Project $project): bool
    {
        return $user->belongsToTeam($project->team);
    }

    /**
     * Determine whether the user can view a list of projects
     *
     * @return bool
     */
    public function viewAny(): bool
    {
        return true;
    }
}
