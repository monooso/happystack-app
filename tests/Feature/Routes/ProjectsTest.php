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
    public function indexDisplaysTheProjectsListIfTheTeamIsOnATrial()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function indexDisplaysTheProjectsListIfTheTeamIsSubscribed()
    {
        $planId = $this->faker->randomNumber();
        $team = Team::factory()->withActiveSubscription($planId)->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function indexRedirectsToTheBillingPageIfTheTeamIsNotOnATrialOrSubscribed()
    {
        $team = Team::factory()->withExpiredTrial()->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.index'));

        $response->assertRedirect('/billing/team');
    }

    /** @test */
    public function createDisplaysTheCreateProjectFormIfTheTeamIsOnATrial()
    {
        $team = Team::factory()->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.create'));

        $response->assertStatus(200);
    }

    /** @test */
    public function createDisplaysTheCreateProjectFormIfTheTeamIsSubscribed()
    {
        $planId = $this->faker->randomNumber();
        $team = Team::factory()->withActiveSubscription($planId)->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.create'));

        $response->assertStatus(200);
    }

    /** @test */
    public function createRedirectsToTheBillingPageIfTheTeamIsNotOnATrialOrSubscribed()
    {
        $team = Team::factory()->withExpiredTrial()->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.create'));

        $response->assertRedirect('/billing/team');
    }

    /** @test */
    public function editDisplaysTheEditProjectFormIfTheTeamIsOnATrial()
    {
        $team = Team::factory()->create();
        $project = Project::factory()->for($team)->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.edit', [$project]));

        $response->assertStatus(200);
    }

    /** @test */
    public function editDisplaysTheEditProjectFormIfTheTeamIsSubscribed()
    {
        $planId = $this->faker->randomNumber();
        $team = Team::factory()->withActiveSubscription($planId)->create();
        $project = Project::factory()->for($team)->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.edit', [$project]));

        $response->assertStatus(200);
    }

    /** @test */
    public function editRedirectsToTheBillingPageIfTheTeamIsNotOnATrialOrSubscribed()
    {
        $team = Team::factory()->withExpiredTrial()->create();
        $project = Project::factory()->for($team)->create();

        $this->actingAs($team->owner);

        $response = $this->get(route('projects.edit', [$project]));

        $response->assertRedirect('/billing/team');
    }
}
