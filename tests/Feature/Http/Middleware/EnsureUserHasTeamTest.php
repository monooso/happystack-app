<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\EnsureUserHasTeam;
use App\Models\Team;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

final class EnsureUserHasTeamTest extends TestCase
{
    use RefreshDatabase;

    private string $testRoute = '_tests/middleware/ensure-user-has-team';

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(EnsureUserHasTeam::class)->get(
            $this->testRoute,
            fn () => 'ok'
        );
    }

    /** @test */
    public function itRedirectsToTheHomeRouteIfTheUserDoesNotExist()
    {
        $this->get($this->testRoute)->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function itRedirectsToTheCreateFirstTeamRouteIfTheUserDoesNotBelongToATeam()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get($this->testRoute);

        $response->assertRedirect(route('teams.create-first'));
    }

    /** @test */
    public function itDoesNotRedirectIfTheUserBelongsToATeam()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $response = $this->get($this->testRoute);

        $response->assertOk();
    }

    /** @test */
    public function itEnsuresTheUsersCurrentTeamIsSet()
    {
        $team = Team::factory()->create();
        $user = tap($team->owner)->update(['current_team_id' => null]);

        $this->actingAs($user);

        $this->get($this->testRoute);

        $this->assertSame($team->id, $user->current_team_id);
    }
}
