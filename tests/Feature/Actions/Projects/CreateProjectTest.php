<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProject;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class CreateProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCreatesANewProject()
    {
        $team = Team::factory()->create();

        $componentIds = $this->createComponents()->pluck('id')->all();

        $attributes = ['name' => 'Test Project', 'components' => $componentIds];

        (new CreateProject())->create($team->owner, $attributes);

        $this->assertDatabaseHas('projects', [
            'team_id' => $team->id,
            'name'    => 'Test Project',
        ]);
    }

    /** @test */
    public function itReturnsTheNewProject(): void
    {
        $team = Team::factory()->create();

        $project = (new CreateProject())->create($team->owner, [
            'name'       => 'XYZ',
            'components' => $this->createComponents()->pluck('id')->all(),
        ]);

        $this->assertInstanceOf(Project::class, $project);
    }

    /** @test */
    public function itAssociatesComponentsWithTheProject(): void
    {
        $team = Team::factory()->create();

        $componentIds = $this->createComponents(5)->pluck('id')->random(2)->all();

        $attributes = ['name' => 'Test Project', 'components' => $componentIds];

        $project = (new CreateProject())->create($team->owner, $attributes);

        $this->assertDatabaseCount('component_project', 2);

        $this->assertDatabaseHas('component_project', [
            'component_id' => $componentIds[0],
            'project_id'   => $project->id,
        ]);

        $this->assertDatabaseHas('component_project', [
            'component_id' => $componentIds[1],
            'project_id'   => $project->id,
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNameIsEmpty(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'name'       => '',
                'components' => $this->createComponents()->pluck('id')->all(),
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNameIsTooLong(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'name'       => str_repeat('X', 256),
                'components' => $this->createComponents()->pluck('id')->all(),
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfThereAreNoSelectedComponents(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'name'       => 'Test Project',
                'components' => [],
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('components'));
        }
    }

    /**
     * Create test service components
     *
     * @param int $count
     *
     * @return Collection
     */
    private function createComponents(int $count = 3): Collection
    {
        return Component::factory()->count($count)->forService()->create();
    }
}
