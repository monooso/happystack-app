<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('user')) {
    /**
     * Get the active user
     *
     * @return Authenticatable|User|null
     */
    function user(): Authenticatable | User | null
    {
        return auth()->user();
    }
}
