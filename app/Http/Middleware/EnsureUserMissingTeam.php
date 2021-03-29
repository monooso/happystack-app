<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

final class EnsureUserMissingTeam
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || $user->belongsToATeam()) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
