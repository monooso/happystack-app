<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\DeleteProject;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

final class DeleteProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itDeletesAProject()
    {
        $project = Project::factory()->create();

        (new DeleteProject())->delete($project->team->owner, $project);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function itThrowsAnAuthExceptionIfTheUserDoesNotHaveTheRequiredPermissions()
    {
        Jetstream::role('minion', 'Minion', [])->description('Underling');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user->id, ['role' => 'minion']);

        $this->expectException(AuthorizationException::class);

        (new DeleteProject())->delete($user, $project);
    }
}
