<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Projects;

use App\Actions\Projects\CreateProject;
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
    public function itCreatesTheProjectAgency()
    {
        $attributes = $this->makeAttributes();

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('agencies', [
            'project_id' => $project->id,
            'via_mail'   => $attributes['agency']['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $attributes['agency']['mail_route'],
        ]);
    }

    /** @test */
    public function itCreatesTheProjectClient()
    {
        $attributes = $this->makeAttributes();
        $attributes['client']['via_mail'] = ToggleValue::ENABLED;

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('clients', [
            'project_id'   => $project->id,
            'via_mail'     => $attributes['client']['via_mail'] === ToggleValue::ENABLED,
            'mail_route'   => $attributes['client']['mail_route'],
            'mail_message' => $attributes['client']['mail_message'],
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
        $attributes = $this->makeAttributes([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => null,
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsInvalid(): void
    {
        $attributes = $this->makeAttributes([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => 'not-an-email',
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheAgencyMailRouteIsTooLong(): void
    {
        $attributes = $this->makeAttributes([
            'agency' => [
                'via_mail'   => ToggleValue::ENABLED,
                'mail_route' => str_repeat('x', 255) . '@example.com',
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('agency.mail_route'));
        }
    }

    /** @test */
    public function itAllowsEmptyAgencyMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $attributes = $this->makeAttributes([
            'agency' => [
                'via_mail'   => ToggleValue::DISABLED,
                'mail_route' => '',
            ],
        ]);

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('projects', ['name' => $attributes['name']]);

        $this->assertDatabaseHas('agencies', [
            'project_id' => $project->id,
            'via_mail'   => false,
            'mail_route' => '',
        ]);
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsMissing(): void
    {
        $attributes = $this->makeAttributes([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => null,
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsInvalid(): void
    {
        $attributes = $this->makeAttributes([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => 'not-an-email',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailRouteIsTooLong(): void
    {
        $attributes = $this->makeAttributes([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => str_repeat('x', 255) . '@example.com',
                'mail_message' => $this->faker->realText(),
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_route'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsMissing(): void
    {
        $attributes = $this->makeAttributes([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => $this->faker->email,
                'mail_message' => null,
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itThrowsAValidationErrorIfTheClientMailMessageIsTooLong(): void
    {
        $attributes = $this->makeAttributes([
            'client' => [
                'via_mail'     => ToggleValue::ENABLED,
                'mail_route'   => $this->faker->email,
                'mail_message' => str_repeat('x', 60001),
            ],
        ]);

        try {
            $this->createProject($attributes);
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->getMessageBag()->has('client.mail_message'));
        }
    }

    /** @test */
    public function itAllowsEmptyClientMailDetailsIfMailNotificationsAreDisabled(): void
    {
        $attributes = $this->makeAttributes([
            'client' => [
                'via_mail'     => ToggleValue::DISABLED,
                'mail_route'   => '',
                'mail_message' => '',
            ],
        ]);

        $project = $this->createProject($attributes);

        $this->assertDatabaseHas('projects', ['name' => $attributes['name']]);

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
            'name'       => $this->faker->company,
            'components' => $this->createComponents()->pluck('id')->all(),
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

        return array_merge($defaults, $attributes);
    }
}
