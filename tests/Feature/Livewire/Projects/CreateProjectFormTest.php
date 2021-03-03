<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Projects;

use App\Http\Livewire\Projects\CreateProjectForm;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire;
use Tests\TestCase;

final class CreateProjectFormTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itCreatesAProject()
    {
        $components = $this->createComponents()->pluck('id')->all();
        $projectName = $this->faker->company;

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('notificationEmail', $this->faker->email)
            ->set('notifyClient', false)
            ->set('projectName', $projectName)
            ->set('projectComponents', $components)
            ->call('create');

        $this->assertTrue(Project::whereName($projectName)->exists());
    }

    /** @test */
    public function itAssociatesTheComponentsWithTheNewProject()
    {
        $components = $this->createComponents(5)->pluck('id')->random(2)->all();
        $projectName = $this->faker->company;

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('notificationEmail', $this->faker->email)
            ->set('notifyClient', false)
            ->set('projectName', $projectName)
            ->set('projectComponents', $components)
            ->call('create');

        $project = Project::whereName($projectName)->first();

        $this->assertSame(2, DB::table('component_project')->where('project_id', $project->id)->count());
    }

    /** @test */
    public function theProjectNameIsRequired()
    {
        $components = $this->createComponents()->pluck('id')->all();

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('notificationEmail', $this->faker->email)
            ->set('notifyClient', false)
            ->set('projectName', '')
            ->set('projectComponents', $components)
            ->call('create')
            ->assertHasErrors(['projectName' => 'required']);
    }

    /** @test */
    public function theProjectNameCannotBeMoreThan255Characters()
    {
        $components = $this->createComponents()->pluck('id')->all();

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('notificationEmail', $this->faker->email)
            ->set('notifyClient', false)
            ->set('projectName', str_repeat('x', 256))
            ->set('projectComponents', $components)
            ->call('create')
            ->assertHasErrors(['projectName' => 'max']);
    }

    /** @test */
    public function atLeastOneComponentIsRequired()
    {
        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('notificationEmail', $this->faker->email)
            ->set('notifyClient', false)
            ->set('projectName', $this->faker->company)
            ->set('projectComponents', [])
            ->call('create')
            ->assertHasErrors(['projectComponents' => 'required']);
    }

    /** @test */
    public function theComponentsMustExist()
    {
        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('notificationEmail', $this->faker->email)
            ->set('notifyClient', false)
            ->set('projectName', $this->faker->company)
            ->set('projectComponents', [$this->faker->randomNumber(6)])
            ->call('create')
            ->assertHasErrors(['projectComponents.0' => 'exists']);
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
}
