<?php

namespace App\Providers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Spark\Plan;
use Spark\Spark;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Spark::billable(Team::class)->resolve(function (Request $request) {
            return $request->user()->currentTeam;
        });

        Spark::billable(Team::class)->authorize(function (Team $billable, Request $request) {
            return $request->user() && $request->user()->ownsTeam($billable);
        });

        Spark::billable(Team::class)->checkPlanEligibility(function (Team $billable, Plan $plan) {
            if ($billable->projects()->count() > $plan->options['projects'] ?? INF) {
                throw ValidationException::withMessages([
                    'plan' => 'You have too many projects for the selected plan.',
                ]);
            }

            if ($billable->users()->count() > $plan->options['members'] ?? INF) {
                throw ValidationException::withMessages([
                    'plan' => 'You have too many team members for the selected plan.',
                ]);
            }
        });
    }
}
