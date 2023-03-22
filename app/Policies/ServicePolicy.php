<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ServicePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Service $service): bool
    {
        return $service->isRestricted()
            ? in_array($user->email, config('happystack.super_users'))
            : true;
    }
}
