<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\EnsureUserMissingTeam;
use App\Models\Team;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

final class EnsureUserMissingTeamTest extends TestCase
{
    use RefreshDatabase;

    private string $testRoute = '_tests/middleware/ensure-user-missing-team';

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(EnsureUserMissingTeam::class)->get(
            $this->testRoute,
            fn () => 'ok'
        );
    }

    /** @test */
    public function itAllowsTheRequestToProceedIfTheUserDoesNotBelongToATeam()
    {
        $this->actingAs(User::factory()->create());

        $this->get($this->testRoute)->assertOk();
    }

    /** @test */
    public function itRedirectsToTheHomeRouteIfTheUserDoesNotExist()
    {
        $this->get($this->testRoute)->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function itRedirectsToTheHomeRouteIfTheUserBelongsToATeam()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $this->get($this->testRoute)->assertRedirect(RouteServiceProvider::HOME);
    }
}
