<?php

declare(strict_types=1);

namespace Tests\Feature\Mail;

use App\Mail\AgencyComponentStatusChanged;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AgencyComponentStatusChangedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itIncludesTheProjectName()
    {
        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();

        $mailable = new AgencyComponentStatusChanged($project, $component);

        $mailable->assertSeeInHtml($project->name);
        $mailable->assertSeeInText($project->name);
    }

    /** @test */
    public function itIncludesTheFullComponentName()
    {
        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();

        $mailable = new AgencyComponentStatusChanged($project, $component);

        $expected = $component->service->name.' '.$component->name;

        $mailable->assertSeeInHtml($expected);
        $mailable->assertSeeInText($expected);
    }
}
