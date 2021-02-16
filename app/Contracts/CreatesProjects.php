<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Project;
use App\Models\User;

interface CreatesProjects
{
    /**
     * Create a new project for the given user
     *
     * @param User $user
     * @param array $attributes
     *
     * @return Project
     */
    public function create(User $user, array $attributes): Project;
}
