<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Events\StatusUpdated;
use App\Listeners\SendAgencyNotifications;
use App\Models\Agency;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class SendAgencyNotificationsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itSendsAnEmailForEachAffectedProject()
    {
        Notification::fake();

        $component = Component::factory()->create();

        $projects = Project::factory()->count(3)->hasAttached($component)->create();

        $agencies = $projects->map(
            fn ($project) => Agency::factory()->for($project)->create()
        );

        (new SendAgencyNotifications())->handle(new StatusUpdated($component));

        $agencies->each(fn ($agency) => Notification::assertSentTo(
            $agency,
            AgencyComponentStatusChanged::class
        ));
    }
}
