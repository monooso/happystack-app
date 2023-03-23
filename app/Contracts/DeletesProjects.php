<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Project;
use App\Models\User;

interface DeletesProjects
{
    /**
     * Deletes the given project
     */
    public function delete(User $user, Project $project): void;
}
