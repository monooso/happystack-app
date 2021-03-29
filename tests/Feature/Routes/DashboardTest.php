<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dashboardRedirectsToTheLoginPageIfTheUserIsLoggedOut()
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    /** @test */
    public function dashboardRedirectsToTheProjectsIndexIfTheUserIsLoggedIn()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $this->get(route('dashboard'))->assertRedirect(route('projects.index'));
    }
}
