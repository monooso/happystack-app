<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\UpdateProject;
use App\Constants\ToggleValue;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class UpdateProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itUpdatesAProject()
    {
        $project = Project::factory()->create(['name' => 'old']);

        $this->actingAs($project->team->owner);

        $input = $this->makeInput(['name' => $this->faker->company]);

        $this->updateProject($project, $input);

        $this->assertDatabaseHas('projects', [
            'id'   => $project->id,
            'name' => $input['name'],
        ]);
    }

    /** @test */
    public function itReturnsTheUpdatedProject(): void
    {
        $project = Project::factory()->create(['name' => 'old']);

        $this->actingAs($project->team->owner);

        $input = $this->makeInput(['name' => $this->faker->company]);

        $this->assertSame(
            $project->id,
            ($this->updateProject($project, $input))->id
        );
    }

    /** @test */
    public function itUpdatesTheProjectComponents(): void
    {
        $components = Component::inRandomOrder()->limit(3)->pluck('id')->all();

        $project = Project::factory()->create();
        $project->components()->sync([$components[0]]);

        $this->actingAs($project->team->owner);

        // Update the components
        $project = $this->updateProject(
            $project,
            ['components' => array_slice($components, 1)]
        );

        $this->assertDatabaseCount('component_project', 2);

        $this->assertDatabaseMissing('component_project', [
            'component_id' => $components[0],
            'project_id'   => $project->id,
        ]);

        $this->assertDatabaseHas('component_project', [
            'component_id' => $components[1],
            'project_id'   => $project->id,
        ]);

        $this->assertDatabaseHas('component_project', [
            'component_id' => $components[2],
            'project_id'   => $project->id,
        ]);
    }

    /** @test */
    public function itUpdatesTheProjectAgency()
    {
        $project = Project::factory()
            ->hasAgency(['mail_route' => 'old@email.com'])
            ->create();

        $this->actingAs($project->team->owner);

        $input = $this->makeInput();
        $input['agency']['mail_route'] = 'new@email.com';

        $this->updateProject($project, $input);

        $this->assertDatabaseHas('agencies', [
            'project_id' => $project->id,
            'mail_route' => $input['agency']['mail_route'],
        ]);
    }

    /** @test */
    public function itUpdatesTheProjectClient()
    {
        $project = Project::factory()
            ->hasClient(['mail_route' => 'old@email.com'])
            ->create();

        $this->actingAs($project->team->owner);

        $input = $this->makeInput();
        $input['client']['mail_route'] = 'new@email.com';

        $this->updateProject($project, $input);

        $this->assertDatabaseHas('clients', [
            'project_id' => $project->id,
            'mail_route' => $input['client']['mail_route'],
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheProjectNameIsEmpty(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        try {
            $this->updateProject($project, ['name' => '']);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheProjectNameIsTooLong(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        try {
            $this->updateProject($project, ['name' => str_repeat('x', 256)]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsMissing(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => null,
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsInvalid(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => 'not-an-email',
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsTooLong(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => str_repeat('x', 255) . '@example.com',
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itAllowsEmptyAgencyMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::DISABLED,
                'mail_route' => '',
            ],
        ]);

        $project = $this->updateProject($project, $input);

        $this->assertDatabaseHas('projects', ['name' => $input['name']]);

        $this->assertDatabaseHas('agencies', [
            'project_id' => $project->id,
            'via_mail'   => false,
            'mail_route' => '',
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsMissing(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => null,
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsInvalid(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => 'not-an-email',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsTooLong(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => str_repeat('x', 255) . '@example.com',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsMissing(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => $this->faker->email,
                'mail_message' => null,
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsTooLong(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => $this->faker->email,
                'mail_message' => str_repeat('x', 60001),
            ],
        ]);

        try {
            $this->updateProject($project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itAllowsEmptyClientMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::DISABLED,
                'mail_route'   => '',
                'mail_message' => '',
            ],
        ]);

        $project = $this->updateProject($project, $input);

        $this->assertDatabaseHas('projects', ['name' => $input['name']]);

        $this->assertDatabaseHas('clients', [
            'project_id'   => $project->id,
            'via_mail'     => false,
            'mail_route'   => '',
            'mail_message' => '',
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfThereAreNoSelectedComponents(): void
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        try {
            $this->updateProject($project, ['components' => []]);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('components'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfAComponentDoesNotExist()
    {
        $project = Project::factory()->create();
        $this->actingAs($project->team->owner);

        try {
            $this->updateProject(
                $project,
                ['components' => [$this->faker->randomNumber(6)]]
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('components.0'));
        }
    }

    /**
     * Update the given project
     *
     * @param Project $project
     * @param array   $input
     *
     * @return Project
     * @throws ValidationException
     */
    private function updateProject(Project $project, array $input = []): Project
    {
        return (new UpdateProject())->update(
            auth()->user(),
            $project,
            $this->makeInput($input)
        );
    }

    /**
     * Get an array of valid project attributes
     *
     * @param array $overrides
     *
     * @return array
     */
    private function makeInput(array $overrides = []): array
    {
        $defaults = [
            'name'       => $this->faker->company,
            'components' => Component::inRandomOrder()->limit(5)->pluck('id')->all(),
            'agency'     => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => $this->faker->email,
            ],
            'client' => [
                'via_mail'     => $this->faker->randomElement(ToggleValue::all()),
                'mail_route'   => $this->faker->email,
                'mail_message' => $this->faker->text,
            ],
        ];

        return array_merge($defaults, $overrides);
    }
}
