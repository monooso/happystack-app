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
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'project:add');
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
        return $user->hasTeamPermission($project->team, 'project:delete');
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
        return $user->hasTeamPermission($project->team, 'project:edit');
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
        return $user->hasTeamPermission($project->team, 'project:read');
    }

    /**
     * Determine whether the user can view a list of projects
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'project:browse');
    }
}
