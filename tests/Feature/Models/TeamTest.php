<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agency;
use App\Models\Client;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function purgeDeletesTheAssociatedProjects()
    {
        $team = Team::factory()->create();
        $projects = Project::factory(3)->for($team)->create();

        foreach ($projects as $project) {
            $this->assertDatabaseHas('projects', ['id' => $project->id]);
        }

        $team->purge();

        foreach ($projects as $project) {
            $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        }
    }

    /** @test */
    public function purgeDeletesTheProjectAgency()
    {
        $team = Team::factory()->create();
        $project = Project::factory()->for($team)->create();
        $agency = Agency::factory()->for($project)->create();

        $this->assertDatabaseHas('agencies', ['id' => $agency->id]);

        $team->purge();

        $this->assertDatabaseMissing('agencies', ['id' => $agency->id]);
    }

    /** @test */
    public function purgeDeletesTheProjectClient()
    {
        $team = Team::factory()->create();
        $project = Project::factory()->for($team)->create();
        $client = Client::factory()->for($project)->create();

        $this->assertDatabaseHas('clients', ['id' => $client->id]);

        $team->purge();

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    /** @test */
    public function purgeDeletesTheProjectComponents()
    {
        $team = Team::factory()->create();
        $component = Component::factory()->create();

        $project = Project::factory()
            ->for($team)
            ->hasAttached($component)
            ->create();

        $this->assertDatabaseHas('component_project', [
            'component_id' => $component->id,
            'project_id'   => $project->id,
        ]);

        $team->purge();

        $this->assertDatabaseMissing('component_project', [
            'component_id' => $component->id,
            'project_id'   => $project->id,
        ]);
    }

    /** @test */
    public function purgeDetachesTeamUsers()
    {
        $user = User::factory()->create();
        $team = Team::factory()->hasAttached($user)->create();

        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $user->id
        ]);

        $team->purge();

        $this->assertDatabaseMissing('team_user', [
            'team_id' => $team->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function purgeDeletesPendingInvitations()
    {
        $team = Team::factory()->create();
        $invitation = TeamInvitation::factory()->for($team)->create();

        $this->assertDatabaseHas('team_invitations', ['id' => $invitation->id]);

        $team->purge();

        $this->assertDatabaseMissing('team_invitations', ['id' => $invitation->id]);
    }
}
