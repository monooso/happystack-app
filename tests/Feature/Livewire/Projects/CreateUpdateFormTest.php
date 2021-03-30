<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Projects;

use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Contracts\UpdatesProjects;
use App\Http\Livewire\Projects\CreateUpdateForm;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire;
use Tests\TestCase;

final class CreateUpdateFormTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itEnablesTheAgencyViaMailPropertyToTrueForNewProjects()
    {
        $project = new Project();
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = new CreateUpdateForm();
        $component->mount($project);

        $this->assertSame(ToggleValue::ENABLED, $component->agency['via_mail']);
    }

    /** @test */
    public function itSetsTheAgencyMailRoutePropertyToTheAuthorizedUserForNewProjects()
    {
        $project = new Project();
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = new CreateUpdateForm();
        $component->mount($project);

        $this->assertSame($user->email, $component->agency['mail_route']);
    }

    /** @test */
    public function savingANewProjectCallsTheCreateActionWithTheCorrectParameters()
    {
        $user = Team::factory()->create()->owner;

        $this->actingAs($user);

        $expected = [
            'name'   => $this->faker->company,
            'agency' => [
                'via_email'  => ToggleValue::ENABLED,
                'mail_route' => $this->faker->email,
            ],
            'client' => [
                'via_mail'     => $this->faker->randomElement(ToggleValue::all()),
                'mail_route'   => $this->faker->email,
                'mail_message' => $this->faker->text,
            ],
            'components' => [$this->faker->randomNumber()],
        ];

        $createsAction = $this->mock(CreatesProjects::class);
        $updatesAction = $this->mock(UpdatesProjects::class);

        $createsAction
            ->expects('create')
            ->once()
            ->with($user, $expected)
            ->andReturns(new Project());

        $updatesAction->expects('update')->never();

        Livewire::test(CreateUpdateForm::class)
            ->set('name', $expected['name'])
            ->set('agency', $expected['agency'])
            ->set('client', $expected['client'])
            ->set('components', $expected['components'])
            ->call('save', $createsAction);
    }

    /** @test */
    public function savingAnExistingProjectCallsTheUpdateAction()
    {
        $project = Project::factory()->create();

        $this->actingAs(User::factory()->create());

        $createsAction = $this->mock(CreatesProjects::class);
        $updatesAction = $this->mock(UpdatesProjects::class);

        $updatesAction->expects('update')->once()->andReturns($project);
        $createsAction->expects('create')->never();

        Livewire::test(CreateUpdateForm::class, ['project' => $project])
            ->call('save', $createsAction, $updatesAction);
    }
}
