<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Contracts\DeletesProjects;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class DeleteProject implements DeletesProjects
{
    public function delete(User $user, Project $project): void
    {
        Gate::forUser($user)->authorize('delete', $project);

        $project->delete();
    }
}
