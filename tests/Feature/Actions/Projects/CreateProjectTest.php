<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProject;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class CreateProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itCreatesANewProject()
    {
        $team = Team::factory()->create();
        $projectName = $this->faker->company;

        $componentIds = $this->createComponents()->pluck('id')->all();

        $attributes = [
            'notificationEmail' => $this->faker->email,
            'notifyClient'      => false,
            'projectComponents' => $componentIds,
            'projectName'       => $projectName,
        ];

        (new CreateProject())->create($team->owner, $attributes);

        $this->assertDatabaseHas('projects', ['team_id' => $team->id, 'name' => $projectName]);
    }

    /** @test */
    public function itReturnsTheNewProject(): void
    {
        $team = Team::factory()->create();

        $project = (new CreateProject())->create($team->owner, [
            'notificationEmail' => $this->faker->email,
            'notifyClient'      => false,
            'projectComponents' => $this->createComponents()->pluck('id')->all(),
            'projectName'       => $this->faker->company,
        ]);

        $this->assertInstanceOf(Project::class, $project);
    }

    /** @test */
    public function itAssociatesComponentsWithTheProject(): void
    {
        $team = Team::factory()->create();

        $componentIds = $this->createComponents(5)->pluck('id')->random(2)->all();

        $attributes = [
            'notificationEmail' => $this->faker->email,
            'notifyClient'      => false,
            'projectComponents' => $componentIds,
            'projectName'       => $this->faker->company,
        ];

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
    public function itThrowsAValidationErrorIfTheProjectNameIsEmpty(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'notificationEmail' => $this->faker->email,
                'notifyClient'      => false,
                'projectComponents' => $this->createComponents()->pluck('id')->all(),
                'projectName'       => '',
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('projectName'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheProjectNameIsTooLong(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'notificationEmail' => $this->faker->email,
                'notifyClient'      => false,
                'projectComponents' => $this->createComponents()->pluck('id')->all(),
                'projectName'       => str_repeat('x', 256),
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('projectName'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNotificationEmailIsMissing(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'notificationEmail' => '',
                'notifyClient'      => false,
                'projectComponents' => $this->createComponents()->pluck('id')->all(),
                'projectName'       => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('notificationEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNotificationEmailIsInvalid(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'notificationEmail' => $this->faker->word,
                'notifyClient'      => false,
                'projectComponents' => $this->createComponents()->pluck('id')->all(),
                'projectName'       => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('notificationEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNotificationEmailIsTooLong(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'notificationEmail' => str_repeat('x', 256) . '@example.com',
                'notifyClient'      => false,
                'projectComponents' => $this->createComponents()->pluck('id')->all(),
                'projectName'       => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('notificationEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfThereAreNoSelectedComponents(): void
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'notificationEmail' => $this->faker->email,
                'notifyClient'      => false,
                'projectComponents' => [],
                'projectName'       => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('projectComponents'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfAComponentDoesNotExist()
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'notificationEmail' => $this->faker->email,
                'notifyClient'      => false,
                'projectComponents' => [$this->faker->randomNumber(6)],
                'projectName'       => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('projectComponents.0'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientNameIsMissing()
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'clientNotificationEmail' => $this->faker->email,
                'clientNotificationName'  => '',
                'notificationEmail'       => $this->faker->email,
                'notifyClient'            => true,
                'projectComponents'       => $this->createComponents()->pluck('id')->all(),
                'projectName'             => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientNotificationName'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientNameIsTooLong()
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'clientNotificationEmail' => $this->faker->email,
                'clientNotificationName'  => str_repeat('x', 256),
                'notificationEmail'       => $this->faker->email,
                'notifyClient'            => true,
                'projectComponents'       => $this->createComponents()->pluck('id')->all(),
                'projectName'             => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientNotificationName'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientEmailIsMissing()
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'clientNotificationEmail' => '',
                'clientNotificationName'  => $this->faker->name,
                'notificationEmail'       => $this->faker->email,
                'notifyClient'            => true,
                'projectComponents'       => $this->createComponents()->pluck('id')->all(),
                'projectName'             => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientNotificationEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientEmailIsInvalid()
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'clientNotificationEmail' => $this->faker->word,
                'clientNotificationName'  => $this->faker->name,
                'notificationEmail'       => $this->faker->email,
                'notifyClient'            => true,
                'projectComponents'       => $this->createComponents()->pluck('id')->all(),
                'projectName'             => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientNotificationEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientEmailIsTooLong()
    {
        $team = Team::factory()->create();

        try {
            (new CreateProject())->create($team->owner, [
                'clientNotificationEmail' => str_repeat('x', 256) . '@example.com',
                'clientNotificationName'  => $this->faker->name,
                'notificationEmail'       => $this->faker->email,
                'notifyClient'            => true,
                'projectComponents'       => $this->createComponents()->pluck('id')->all(),
                'projectName'             => $this->faker->company,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientNotificationEmail'));
        }
    }

    /** @test */
    public function itAllowsEmptyClientDetailsIfNotifyClientIsFalse()
    {
        $team = Team::factory()->create();
        $projectName = $this->faker->company;

        (new CreateProject())->create($team->owner, [
            'clientNotificationEmail' => '',
            'clientNotificationName'  => '',
            'notificationEmail'       => $this->faker->email,
            'notifyClient'            => false,
            'projectComponents'       => $this->createComponents()->pluck('id')->all(),
            'projectName'             => $projectName,
        ]);

        $this->assertDatabaseHas('projects', [
            'team_id'                   => $team->id,
            'name'                      => $projectName,
            'client_notification_email' => '',
            'client_notification_name'  => '',
        ]);
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
