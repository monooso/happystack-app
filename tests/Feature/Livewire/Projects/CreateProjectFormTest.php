<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Projects;

use App\Http\Livewire\Projects\CreateProjectForm;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire;
use Tests\TestCase;

final class CreateProjectFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCreatesAProject()
    {
        $components = $this->createComponents()->pluck('id')->all();

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('name', 'Test Project')
            ->set('components', $components)
            ->call('create');

        $this->assertTrue(Project::whereName('Test Project')->exists());
    }

    /** @test */
    public function itAssociatesTheComponentsWithTheNewProject()
    {
        $components = $this->createComponents(5)->pluck('id')->random(2)->all();

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('name', 'Test Project')
            ->set('components', $components)
            ->call('create');

        $project = Project::whereName('Test Project')->first();

        $this->assertSame(2, DB::table('component_project')->where('project_id', $project->id)->count());
    }

    /** @test */
    public function theProjectNameIsRequired()
    {
        $components = $this->createComponents()->pluck('id')->all();

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('name', '')
            ->set('components', $components)
            ->call('create')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function theProjectNameCannotBeMoreThan255Characters()
    {
        $components = $this->createComponents()->pluck('id')->all();

        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('name', str_repeat('x', 256))
            ->set('components', $components)
            ->call('create')
            ->assertHasErrors(['name' => 'max']);
    }

    /** @test */
    public function atLeastOneComponentIsRequired()
    {
        $this->actingAs(Team::factory()->create()->owner);

        Livewire::test(CreateProjectForm::class)
            ->set('name', 'Test Project')
            ->set('components', [])
            ->call('create')
            ->assertHasErrors(['components' => 'required']);
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
