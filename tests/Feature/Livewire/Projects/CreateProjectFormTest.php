<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Projects;

use App\Contracts\CreatesProjects;
use App\Http\Livewire\Projects\CreateProjectForm;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire;
use Tests\TestCase;

final class CreateProjectFormTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function createCallsTheActionWithTheCorrectParameters()
    {
        $user = Team::factory()->create()->owner;

        $this->actingAs($user);

        $expected = [
            'name'          => $this->faker->company,
            'channels'      => ['email' => $this->faker->email],
            'components'    => [$this->faker->randomNumber()],
            'notifyClient'  => $this->faker->boolean,
            'clientEmail'   => $this->faker->email,
            'clientMessage' => $this->faker->text,
        ];

        $action = $this->mock(CreatesProjects::class);

        $action
            ->expects('create')
            ->once()
            ->with($user, $expected)
            ->andReturns(new Project());

        return Livewire::test(CreateProjectForm::class)
            ->set('name', $expected['name'])
            ->set('channels', $expected['channels'])
            ->set('components', $expected['components'])
            ->set('notifyClient', $expected['notifyClient'])
            ->set('clientEmail', $expected['clientEmail'])
            ->set('clientMessage', $expected['clientMessage'])
            ->call('create', $action);
    }
}
