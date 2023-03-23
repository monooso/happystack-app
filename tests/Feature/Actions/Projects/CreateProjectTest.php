<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProject;
use App\Constants\ToggleValue;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

final class CreateProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itCreatesANewProject()
    {
        $input = $this->makeInput();

        $this->createProject($input);

        $this->assertDatabaseHas('projects', ['name' => $input['name']]);
    }

    /** @test */
    public function itReturnsTheNewProject(): void
    {
        $this->assertInstanceOf(Project::class, $this->createProject());
    }

    /** @test */
    public function itAssociatesComponentsWithTheProject(): void
    {
        $input = $this->makeInput();

        $project = $this->createProject($input);

        $this->assertDatabaseCount(
            'component_project',
            count($input['components'])
        );

        foreach ($input['components'] as $componentId) {
            $this->assertDatabaseHas('component_project', [
                'component_id' => $componentId,
                'project_id' => $project->id,
            ]);
        }
    }

    /** @test */
    public function itCreatesTheProjectAgency()
    {
        $input = $this->makeInput();

        $project = $this->createProject($input);

        $this->assertDatabaseHas('agencies', [
            'project_id' => $project->id,
            'via_mail' => $input['agency']['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $input['agency']['mail_route'],
        ]);
    }

    /** @test */
    public function itCreatesTheProjectClient()
    {
        $input = $this->makeInput();
        $input['client']['via_mail'] = ToggleValue::ENABLED;

        $project = $this->createProject($input);

        $this->assertDatabaseHas('clients', [
            'project_id' => $project->id,
            'via_mail' => $input['client']['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $input['client']['mail_route'],
            'mail_message' => $input['client']['mail_message'],
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
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsMissing(): void
    {
        $input = $this->makeInput([
            'agency' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => null,
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsInvalid(): void
    {
        $input = $this->makeInput([
            'agency' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => 'not-an-email',
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsTooLong(): void
    {
        $input = $this->makeInput([
            'agency' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => str_repeat('x', 255).'@example.com',
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itAllowsEmptyAgencyMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $input = $this->makeInput([
            'agency' => [
                'via_mail' => ToggleValue::DISABLED,
                'mail_route' => '',
            ],
        ]);

        $project = $this->createProject($input);

        $this->assertDatabaseHas('projects', ['name' => $input['name']]);

        $this->assertDatabaseHas('agencies', [
            'project_id' => $project->id,
            'via_mail' => false,
            'mail_route' => '',
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsMissing(): void
    {
        $input = $this->makeInput([
            'client' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => null,
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsInvalid(): void
    {
        $input = $this->makeInput([
            'client' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => 'not-an-email',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsTooLong(): void
    {
        $input = $this->makeInput([
            'client' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => str_repeat('x', 255).'@example.com',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsMissing(): void
    {
        $input = $this->makeInput([
            'client' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => $this->faker->email(),
                'mail_message' => null,
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsTooLong(): void
    {
        $input = $this->makeInput([
            'client' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => $this->faker->email(),
                'mail_message' => str_repeat('x', 60001),
            ],
        ]);

        try {
            $this->createProject($input);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itAllowsEmptyClientMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $input = $this->makeInput([
            'client' => [
                'via_mail' => ToggleValue::DISABLED,
                'mail_route' => '',
                'mail_message' => '',
            ],
        ]);

        $project = $this->createProject($input);

        $this->assertDatabaseHas('projects', ['name' => $input['name']]);

        $this->assertDatabaseHas('clients', [
            'project_id' => $project->id,
            'via_mail' => false,
            'mail_route' => '',
            'mail_message' => '',
        ]);
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
    public function itThrowsAnAuthExceptionIfTheUserDoesNotHaveTheRequiredPermissions()
    {
        Jetstream::role('minion', 'Minion', [])->description('Underling');

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $project->team->users()->attach($user->id, ['role' => 'minion']);

        $user->switchTeam($project->team);

        $this->expectException(AuthorizationException::class);

        (new CreateProject())->create($user, []);
    }

    /**
     * Create a project
     *
     *
     * @throws ValidationException
     */
    private function createProject(array $input = []): Project
    {
        $team = Team::factory()->create();

        return (new CreateProject())->create(
            $team->owner,
            $this->makeInput($input)
        );
    }

    /**
     * Get an array of valid project attributes
     */
    private function makeInput(array $input = []): array
    {
        $defaults = [
            'name' => $this->faker->company(),
            'components' => Component::inRandomOrder()->limit(5)->pluck('id')->all(),
            'agency' => [
                'via_mail' => ToggleValue::ENABLED,
                'mail_route' => $this->faker->email(),
            ],
            'client' => [
                'via_mail' => $this->faker->randomElement(ToggleValue::all()),
                'mail_route' => $this->faker->email(),
                'mail_message' => $this->faker->text(),
            ],
        ];

        return array_merge($defaults, $input);
    }
}
