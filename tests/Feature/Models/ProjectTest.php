<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Constants\Status;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function getStatusAttributeReturnsOkayIfAllComponentsAreHealthy()
    {
        $project = Project::factory()
            ->hasComponents(3, ['status' => Status::OKAY])
            ->create();

        $this->assertSame(Status::OKAY, $project->status);
    }

    /** @test */
    public function getStatusAttributeReturnsDownIfAnyComponentsAreDown()
    {
        $project = Project::factory()
            ->hasComponents(3, ['status' => Status::OKAY])
            ->create();

        $project->components[0]->status = Status::WARN;
        $project->components[0]->save();

        $project->components[1]->status = Status::DOWN;
        $project->components[1]->save();

        $this->assertSame(Status::DOWN, $project->status);
    }

    /** @test */
    public function getStatusAttributeReturnsWarnIfThereAreWarningsAndNoComponentsAreDown()
    {
        $project = Project::factory()
            ->hasComponents(3, ['status' => Status::OKAY])
            ->create();

        $project->components[0]->status = Status::WARN;
        $project->components[0]->save();

        $this->assertSame(Status::WARN, $project->status);
    }

    /** @test */
    public function hasUuidTraitGeneratesAUuidWhenCreatingAProject()
    {
        $project = Project::factory()->create();

        $this->assertTrue(Str::isUuid($project->uuid));
    }

    /** @test */
    public function hasUuidTraitDoesNotOverrideAGivenUuid()
    {
        $uuid = Str::uuid();

        $project = Project::factory()->make();
        $project->uuid = $uuid;
        $project->save();

        $this->assertDatabaseHas('projects', [
            'id'   => $project->id,
            'uuid' => $uuid,
        ]);
    }
}
