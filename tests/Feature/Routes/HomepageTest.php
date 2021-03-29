<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomepageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function theHomepageRedirectsToTheLoginPageIfTheUserIsLoggedOut()
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    /** @test */
    public function theHomepageRedirectsToTheProjectsIndexIfTheUserIsLoggedIn()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $this->get('/')->assertRedirect(route('projects.index'));
    }
}
