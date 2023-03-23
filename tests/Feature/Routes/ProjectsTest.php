<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class ProjectsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexRedirectsToTheLoginPageIfTheUserIsLoggedOut()
    {
        $this->get(route('projects.index'))->assertRedirect(route('login'));
    }

    /** @test */
    public function indexDisplaysTheProjectsList()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function createDisplaysTheCreateProjectForm()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.create'));

        $response->assertStatus(200);
    }

    /** @test */
    public function editDisplaysTheEditProjectForm()
    {
        $team = Team::factory()->create();
        $project = Project::factory()->for($team)->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.edit', [$project]));

        $response->assertStatus(200);
    }
}
