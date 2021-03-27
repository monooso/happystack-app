<?php

declare(strict_types=1);

namespace Tests\Feature\Policies;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

final class ProjectPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function createReturnsTrueIfTheUserHasCreatePrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:create']);

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($team);

        $this->assertTrue((new ProjectPolicy())->create($user));
    }

    /** @test */
    public function createReturnsFalseIfTheUserDoesNotHaveCreatePrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('minion', 'Minion', []);

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($team);

        $this->assertFalse((new ProjectPolicy())->create($user));
    }

    /** @test */
    public function deleteReturnsTrueIfTheUserHasDeletePrivilegesOnTheProjectTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:delete']);

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($project->team);

        $this->assertTrue((new ProjectPolicy())->delete($user, $project));
    }

    /** @test */
    public function deleteReturnsFalseIfTheUserDoesNotHaveDeletePrivilegesOnTheProjectTeam()
    {
        Jetstream::role('minion', 'Minion', []);

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($project->team);

        $this->assertFalse((new ProjectPolicy())->delete($user, $project));
    }

    /** @test */
    public function updateReturnsTrueIfTheUserHasUpdatePrivilegesOnTheProjectTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:update']);

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($project->team);

        $this->assertTrue((new ProjectPolicy())->update($user, $project));
    }

    /** @test */
    public function updateReturnsFalseIfTheUserDoesNotHaveUpdatePrivilegesOnTheProjectTeam()
    {
        Jetstream::role('minion', 'Minion', []);

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($project->team);

        $this->assertFalse((new ProjectPolicy())->update($user, $project));
    }

    /** @test */
    public function viewReturnsTrueIfTheUserHasViewPrivilegesOnTheProjectTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:view']);

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($project->team);

        $this->assertTrue((new ProjectPolicy())->view($user, $project));
    }

    /** @test */
    public function viewReturnsFalseIfTheUserDoesNotHaveViewPrivilegesOnTheProjectTeam()
    {
        Jetstream::role('minion', 'Minion', []);

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($project->team);

        $this->assertFalse((new ProjectPolicy())->view($user, $project));
    }

    /** @test */
    public function viewAnyReturnsTrueIfTheUserHasViewPrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:view']);

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($team);

        $this->assertTrue((new ProjectPolicy())->viewAny($user));
    }

    /** @test */
    public function viewAnyReturnsFalseIfTheUserDoesNotHaveViewPrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('minion', 'Minion', []);

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($team);

        $this->assertFalse((new ProjectPolicy())->viewAny($user));
    }
}
