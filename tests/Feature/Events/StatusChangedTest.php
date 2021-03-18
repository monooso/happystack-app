<?php

declare(strict_types=1);

namespace Tests\Feature\Events;

use App\Constants\Status;
use App\Events\StatusChanged;
use App\Models\Agency;
use App\Models\Client;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class StatusChangedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function aStatusChangedEventSendsAgencyNotifications()
    {
        Notification::fake();

        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();
        $agency = Agency::factory()->for($project)->create();

        StatusChanged::dispatch($component);

        Notification::assertSentTo($agency, AgencyComponentStatusChanged::class);
    }

    /** @test */
    public function aStatusChangedEventSendsClientNotifications()
    {
        Notification::fake();

        $component = Component::factory()->create(['status' => Status::DOWN]);

        $project = Project::factory()->hasAttached($component)->create();
        $client = Client::factory()->for($project)->create();

        StatusChanged::dispatch($component);

        Notification::assertSentTo($client, ClientComponentStatusChanged::class);
    }
}
