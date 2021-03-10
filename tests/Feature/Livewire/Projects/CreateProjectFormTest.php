<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Projects;

use App\Constants\ToggleValue;
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
            'name'           => $this->faker->company,
            'agencyChannels' => [
                'email' => ['enabled' => true, 'route' => $this->faker->email],
            ],
            'clientChannels' => [
                'email' => [
                    'enabled' => $this->faker->randomElement(ToggleValue::all()),
                    'route'   => $this->faker->email,
                    'message' => $this->faker->text,
                ],
            ],
            'components' => [$this->faker->randomNumber()],
        ];

        $action = $this->mock(CreatesProjects::class);

        $action
            ->expects('create')
            ->once()
            ->with($user, $expected)
            ->andReturns(new Project());

        return Livewire::test(CreateProjectForm::class)
            ->set('name', $expected['name'])
            ->set('agencyChannels', $expected['agencyChannels'])
            ->set('clientChannels', $expected['clientChannels'])
            ->set('components', $expected['components'])
            ->call('create', $action);
    }
}
