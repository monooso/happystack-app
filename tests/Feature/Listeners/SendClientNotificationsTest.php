<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Constants\Status;
use App\Events\StatusChanged;
use App\Listeners\SendClientNotifications;
use App\Models\Client;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class SendClientNotificationsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itSendsAnEmailForEachAffectedProject()
    {
        Notification::fake();

        $component = Component::factory()->create(['status' => Status::DOWN]);

        $projects = Project::factory()->count(3)->hasAttached($component)->create();

        $clients = $projects->map(
            fn ($project) => Client::factory()->for($project)->create()
        );

        (new SendClientNotifications())->handle(new StatusChanged($component));

        $clients->each(fn ($client) => Notification::assertSentTo(
            $client,
            ClientComponentStatusChanged::class
        ));
    }

    /** @test */
    public function itDoesNotSendNotificationsIfTheComponentStatusIsOkay()
    {
        Notification::fake();

        $component = Component::factory()->create(['status' => Status::OKAY]);

        $project = Project::factory()->hasAttached($component)->create();

        Client::factory()->for($project)->create();

        (new SendClientNotifications())->handle(new StatusChanged($component));

        Notification::assertNothingSent();
    }
}
