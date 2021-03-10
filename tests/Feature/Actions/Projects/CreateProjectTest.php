<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProject;
use App\Constants\NotificationChannel;
use App\Constants\ToggleValue;
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

        $this->assertDatabaseHas('projects', ['name' => $attributes['name']]);
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
    public function itCreatesTheAgencyEmailChannel()
    {
        $attributes = $this->makeAttributes();

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('agency_channels', [
            'project_id' => $project->id,
            'type'       => NotificationChannel::EMAIL,
            'route'      => $attributes['agencyChannels']['email']['route'],
        ]);
    }

    /** @test */
    public function itCreatesTheClientEmailChannel()
    {
        $attributes = $this->makeAttributes();
        $attributes['clientChannels']['email']['enabled'] = ToggleValue::ENABLED;

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('client_channels', [
            'project_id' => $project->id,
            'type'       => NotificationChannel::EMAIL,
            'route'      => $attributes['clientChannels']['email']['route'],
            'message'    => $attributes['clientChannels']['email']['message'],
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
    public function itThrowsAValidationErrorIfTheAgencyEmailChannelRouteIsMissing(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['agencyChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['agencyChannels']['email']['route'] = null;

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agencyChannels.email.route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyEmailChannelRouteIsInvalid(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['agencyChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['agencyChannels']['email']['route'] = 'not-a-email';

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agencyChannels.email.route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyEmailChannelRouteIsTooLong(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['agencyChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['agencyChannels']['email']['route'] = str_repeat('x', 255) . '@example.com';

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agencyChannels.email.route'));
        }
    }

    /** @test */
    public function itAllowsEmptyAgencyEmailChannelDetailsIfEmailNotificationsAreDisabled(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['agencyChannels']['email']['enabled'] = ToggleValue::DISABLED;
        $attributes['agencyChannels']['email']['route'] = '';

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('projects', ['name' => $attributes['name']]);
        $this->assertDatabaseMissing('agency_channels', ['project_id' => $project->id]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientEmailChannelRouteIsMissing(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['clientChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['clientChannels']['email']['route'] = null;

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientChannels.email.route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientEmailChannelRouteIsInvalid(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['clientChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['clientChannels']['email']['route'] = 'not-an-email';

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientChannels.email.route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientEmailChannelRouteIsTooLong(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['clientChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['clientChannels']['email']['route'] = str_repeat('x', 256) . '@example.com';

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientChannels.email.route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientEmailChannelMessageIsMissing(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['clientChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['clientChannels']['email']['message'] = null;

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientChannels.email.message'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientEmailChannelMessageIsTooLong(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['clientChannels']['email']['enabled'] = ToggleValue::ENABLED;
        $attributes['clientChannels']['email']['message'] = str_repeat('x', 60001);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('clientChannels.email.message'));
        }
    }

    /** @test */
    public function itAllowsEmptyClientEmailChannelDetailsIfEmailNotificationsAreDisabled(): void
    {
        $attributes = $this->makeAttributes();
        $attributes['clientChannels']['email']['enabled'] = ToggleValue::DISABLED;
        $attributes['clientChannels']['email']['route'] = '';
        $attributes['clientChannels']['email']['message'] = '';

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('projects', ['name' => $attributes['name']]);
        $this->assertDatabaseMissing('client_channels', ['project_id' => $project->id]);
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
            'name'           => $this->faker->company,
            'components'     => $this->createComponents()->pluck('id')->all(),
            'agencyChannels' => [
                'email' => [
                    'enabled' => ToggleValue::ENABLED,
                    'route'   => $this->faker->email,
                ],
            ],
            'clientChannels' => [
                'email' => [
                    'enabled' => $this->faker->randomElement(ToggleValue::all()),
                    'route'   => $this->faker->email,
                    'message' => $this->faker->text,
                ],
            ],
        ];

        return array_merge($defaults, $attributes);
    }
}
