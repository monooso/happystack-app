<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\UpdateProject;
use App\Constants\ToggleValue;
use App\Models\Component;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

final class UpdateProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itUpdatesAProject()
    {
        $project = Project::factory()->create(['name' => 'old']);
        $input = $this->makeInput(['name' => $this->faker->company]);

        (new UpdateProject())->update($project->team->owner, $project, $input);

        $this->assertDatabaseHas('projects', [
            'id'   => $project->id,
            'name' => $input['name'],
        ]);
    }

    /** @test */
    public function itReturnsTheUpdatedProject(): void
    {
        $project = Project::factory()->create(['name' => 'old']);
        $input = $this->makeInput(['name' => $this->faker->company]);

        $this->assertSame(
            $project->id,
            (new UpdateProject())->update($project->team->owner, $project, $input)->id
        );
    }

    /** @test */
    public function itUpdatesTheProjectComponents(): void
    {
        $components = Component::inRandomOrder()->limit(3)->pluck('id')->all();

        $project = Project::factory()->create();
        $project->components()->sync([$components[0]]);

        $input = $this->makeInput(['components' => array_slice($components, 1)]);

        (new UpdateProject())->update($project->team->owner, $project, $input);

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

        $input = $this->makeInput();
        $input['agency']['mail_route'] = 'new@email.com';

        (new UpdateProject())->update($project->team->owner, $project, $input);

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

        $input = $this->makeInput();
        $input['client']['mail_route'] = 'new@email.com';

        (new UpdateProject())->update($project->team->owner, $project, $input);

        $this->assertDatabaseHas('clients', [
            'project_id' => $project->id,
            'mail_route' => $input['client']['mail_route'],
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheProjectNameIsEmpty(): void
    {
        $project = Project::factory()->create();
        $input = $this->makeInput(['name' => '']);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheProjectNameIsTooLong(): void
    {
        $project = Project::factory()->create();
        $input = $this->makeInput(['name' => str_repeat('x', 256)]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('name'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsMissing(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => null,
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsInvalid(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => 'not-an-email',
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsTooLong(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => str_repeat('x', 255) . '@example.com',
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itAllowsEmptyAgencyMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'agency' => [
                'via_mail'   => ToggleValue::DISABLED,
                'mail_route' => '',
            ],
        ]);

        (new UpdateProject())->update($project->team->owner, $project, $input);

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

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => null,
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsInvalid(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => 'not-an-email',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsTooLong(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => str_repeat('x', 255) . '@example.com',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsMissing(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => $this->faker->email,
                'mail_message' => null,
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsTooLong(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => $this->faker->email,
                'mail_message' => str_repeat('x', 60001),
            ],
        ]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itAllowsEmptyClientMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $project = Project::factory()->create();

        $input = $this->makeInput([
            'client' => [
                'via_mail'     => ToggleValue::DISABLED,
                'mail_route'   => '',
                'mail_message' => '',
            ],
        ]);

        (new UpdateProject())->update($project->team->owner, $project, $input);

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
        $input = $this->makeInput(['components' => []]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('components'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfAComponentDoesNotExist()
    {
        $project = Project::factory()->create();
        $input = $this->makeInput(['components' => [$this->faker->randomNumber(6)]]);

        try {
            (new UpdateProject())->update($project->team->owner, $project, $input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('components.0'));
        }
    }

    /** @test */
    public function itThrowsAnAuthExceptionIfTheUserDoesNotHaveTheRequiredPermissions()
    {
        Jetstream::role('minion', 'Minion', []);

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user->id, ['role' => 'minion']);

        $this->expectException(AuthorizationException::class);

        (new UpdateProject())->update($user, $project, []);
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
