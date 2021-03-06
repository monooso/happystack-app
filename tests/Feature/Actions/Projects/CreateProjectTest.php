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
        $attributes = $this->makeAttributes();

        $this->createProject($attributes);

        $this->assertDatabaseHas('projects', [
            'name'           => $attributes['name'],
            'notify_client'  => $attributes['notifyClient'],
            'client_email'   => $attributes['clientEmail'],
            'client_message' => $attributes['clientMessage'],
        ]);
    }

    /** @test */
    public function itReturnsTheNewProject(): void
    {
        $this->assertInstanceOf(Project::class, $this->createProject());
    }

    /** @test */
    public function itAssociatesComponentsWithTheProject(): void
    {
        $components = $this->createComponents(5)->pluck('id')->random(2)->all();

        $project = $this->createProject(['components' => $components]);

        $this->assertDatabaseCount('component_project', 2);

        $this->assertDatabaseHas('component_project', [
            'component_id' => $components[0],
            'project_id'   => $project->id,
        ]);

        $this->assertDatabaseHas('component_project', [
            'component_id' => $components[1],
            'project_id'   => $project->id,
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheProjectNameIsEmpty(): void
    {
        try {
            $this->createProject(['name' => '']);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheProjectNameIsTooLong(): void
    {
        try {
            $this->createProject(['name' => str_repeat('x', 256)]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNotificationEmailIsMissing(): void
    {
        try {
            $this->createProject(['channels' => null]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('channels.email'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNotificationEmailIsInvalid(): void
    {
        $team = Team::factory()->create();

        try {
            $this->createProject(['channels' => ['email' => $this->faker->word]]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('channels.email'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheNotificationEmailIsTooLong(): void
    {
        try {
            $email = str_repeat('x', 256) . '@example.com';
            $this->createProject(['channels' => ['email' => $email]]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('channels.email'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfThereAreNoSelectedComponents(): void
    {
        try {
            $this->createProject(['components' => []]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('components'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfAComponentDoesNotExist()
    {
        try {
            $this->createProject(['components' => [$this->faker->randomNumber(6)]]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('components.0'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientEmailIsMissing()
    {
        try {
            $this->createProject(['notifyClient' => true, 'clientEmail' => null]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientEmailIsInvalid()
    {
        try {
            $this->createProject([
                'notifyClient' => true,
                'clientEmail'  => $this->faker->word,
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientEmailIsTooLong()
    {
        try {
            $this->createProject([
                'notifyClient' => true,
                'clientEmail'  => str_repeat('x', 256) . '@example.com',
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientEmail'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientMessageIsMissing()
    {
        try {
            $this->createProject(['notifyClient' => true, 'clientMessage' => null]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientMessage'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfNotifyClientIsTrueAndTheClientMessageIsTooLong()
    {
        try {
            $this->createProject([
                'notifyClient'  => true,
                'clientMessage' => str_repeat('x', 70000),
            ]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientMessage'));
        }
    }

    /** @test */
    public function itAllowsEmptyClientDetailsIfNotifyClientIsFalse()
    {
        $name = $this->faker->company;

        $this->createProject([
            'name'          => $name,
            'notifyClient'  => false,
            'clientEmail'   => '',
            'clientMessage' => '',
        ]);

        $this->assertDatabaseHas('projects', [
            'name'           => $name,
            'notify_client'  => false,
            'client_email'   => '',
            'client_message' => '',
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

    /**
     * Create a project
     *
     * @param array $attributes
     *
     * @return Project
     * @throws ValidationException
     */
    private function createProject(array $attributes = []): Project
    {
        $team = Team::factory()->create();

        return (new CreateProject())->create(
            $team->owner,
            $this->makeAttributes($attributes)
        );
    }

    /**
     * Get an array of valid project attributes
     *
     * @param array $attributes
     *
     * @return array
     */
    private function makeAttributes(array $attributes = []): array
    {
        $defaults = [
            'name'          => $this->faker->company,
            'channels'      => ['email' => $this->faker->email],
            'components'    => $this->createComponents()->pluck('id')->all(),
            'notifyClient'  => $this->faker->boolean,
            'clientEmail'   => $this->faker->email,
            'clientMessage' => $this->faker->text,
        ];

        return array_merge($defaults, $attributes);
    }
}
