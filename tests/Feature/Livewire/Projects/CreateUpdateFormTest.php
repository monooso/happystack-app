<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Projects;

use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Http\Livewire\Projects\CreateUpdateForm;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire;
use Tests\TestCase;

final class CreateUpdateFormTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function createCallsTheActionWithTheCorrectParameters()
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

        $action = $this->mock(CreatesProjects::class);

        $action
            ->expects('create')
            ->once()
            ->with($user, $expected)
            ->andReturns(new Project());

        return Livewire::test(CreateUpdateForm::class)
            ->set('name', $expected['name'])
            ->set('agency', $expected['agency'])
            ->set('client', $expected['client'])
            ->set('components', $expected['components'])
            ->call('create', $action);
    }
}
