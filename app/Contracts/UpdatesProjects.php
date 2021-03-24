<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Project;
use App\Models\User;
use Illuminate\Validation\ValidationException;

interface UpdatesProjects
{
    /**
     * Updates the given project
     *
     * @param User    $user
     * @param Project $project
     * @param array   $input
     *
     * @return Project
     * @throws ValidationException
     */
    public function update(User $user, Project $project, array $input): Project;
}
