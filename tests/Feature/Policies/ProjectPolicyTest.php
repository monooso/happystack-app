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
    public function createReturnsTrueIfTheUserHasAddPrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:add'])->description('King');

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($team);

        $this->assertTrue((new ProjectPolicy())->create($user));
    }

    /** @test */
    public function createReturnsFalseIfTheUserDoesNotHaveAddPrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('minion', 'Minion', [])->description('Underling');

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($team);

        $this->assertFalse((new ProjectPolicy())->create($user));
    }

    /** @test */
    public function deleteReturnsTrueIfTheUserHasDeletePrivilegesOnTheProjectTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:delete'])->description('King');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($project->team);

        $this->assertTrue((new ProjectPolicy())->delete($user, $project));
    }

    /** @test */
    public function deleteReturnsFalseIfTheUserDoesNotHaveDeletePrivilegesOnTheProjectTeam()
    {
        Jetstream::role('minion', 'Minion', [])->description('Underling');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($project->team);

        $this->assertFalse((new ProjectPolicy())->delete($user, $project));
    }

    /** @test */
    public function updateReturnsTrueIfTheUserHasEditPrivilegesOnTheProjectTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:edit'])->description('King');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($project->team);

        $this->assertTrue((new ProjectPolicy())->update($user, $project));
    }

    /** @test */
    public function updateReturnsFalseIfTheUserDoesNotHaveEditPrivilegesOnTheProjectTeam()
    {
        Jetstream::role('minion', 'Minion', [])->description('Underling');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($project->team);

        $this->assertFalse((new ProjectPolicy())->update($user, $project));
    }

    /** @test */
    public function viewReturnsTrueIfTheUserHasReadPrivilegesOnTheProjectTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:read'])->description('King');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($project->team);

        $this->assertTrue((new ProjectPolicy())->view($user, $project));
    }

    /** @test */
    public function viewReturnsFalseIfTheUserDoesNotHaveReadPrivilegesOnTheProjectTeam()
    {
        Jetstream::role('minion', 'Minion', [])->description('Underling');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($project->team);

        $this->assertFalse((new ProjectPolicy())->view($user, $project));
    }

    /** @test */
    public function viewAnyReturnsTrueIfTheUserHasBrowsePrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('overlord', 'Overlord', ['project:browse'])->description('King');

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'overlord']);
        $user->switchTeam($team);

        $this->assertTrue((new ProjectPolicy())->viewAny($user));
    }

    /** @test */
    public function viewAnyReturnsFalseIfTheUserDoesNotHaveBrowsePrivilegesOnTheirCurrentTeam()
    {
        Jetstream::role('minion', 'Minion', [])->description('Underling');

        $team = Team::factory()->create();
        $user = User::factory()->create();

        $team->users()->attach($user, ['role' => 'minion']);
        $user->switchTeam($team);

        $this->assertFalse((new ProjectPolicy())->viewAny($user));
    }
}
