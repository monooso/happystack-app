<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserHasTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (is_null($user)) {
            return redirect(RouteServiceProvider::HOME);
        }

        if (! $user->belongsToATeam()) {
            return redirect()->route('teams.create-first');
        }

        $this->ensureUserHasCurrentTeam($user);

        return $next($request);
    }

    /**
     * Ensure the active user has a valid "current team"
     */
    private function ensureUserHasCurrentTeam(User $user): void
    {
        if (! is_null(user()->current_team_id)) {
            return;
        }

        $user->switchTeam($user->allTeams()->first());
    }
}
