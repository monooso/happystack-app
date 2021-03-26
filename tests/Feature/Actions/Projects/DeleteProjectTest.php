<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\DeleteProject;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

final class DeleteProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itDeletesAProject()
    {
        $project = Project::factory()->create();

        $this->actingAs($project->team->owner);

        (new DeleteProject())->delete(Auth::user(), $project);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function itThrowsAnAuthExceptionIfTheUserDoesNotBelongToTheProjectTeam()
    {
        $project = Project::factory()->create();

        $this->actingAs(User::factory()->create());

        $this->expectException(AuthorizationException::class);

        (new DeleteProject())->delete(Auth::user(), $project);
    }
}
