<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Events\StatusUpdated;
use App\Listeners\SendAgencyNotifications;
use App\Models\AgencyChannel;
use App\Models\ClientChannel;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\AnonymousNotifiable;
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

        Project::factory()->count(3)
            ->has(AgencyChannel::factory())
            ->hasAttached($component)
            ->create();

        (new SendAgencyNotifications())->handle(new StatusUpdated($component));

        Notification::assertSentToTimes(
            new AnonymousNotifiable(),
            AgencyComponentStatusChanged::class,
            3
        );
    }

    /** @test */
    public function itSendsTheEmailToTheAgencyChannel()
    {
        Notification::fake();

        $address = $this->faker->unique()->email;
        $herring = $this->faker->unique()->email;

        $component = Component::factory()->create();

        Project::factory()
            ->has(AgencyChannel::factory(['type' => 'mail', 'route' => $address]))
            ->has(ClientChannel::factory(['type' => 'mail', 'route' => $herring]))
            ->hasAttached($component)
            ->create();

        (new SendAgencyNotifications())->handle(new StatusUpdated($component));

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            AgencyComponentStatusChanged::class,
            fn ($a, $b, $notifiable) => $notifiable->routes['mail'] === $address
        );

        Notification::assertNotSentTo(
            new AnonymousNotifiable(),
            AgencyComponentStatusChanged::class,
            fn ($a, $b, $notifiable) => $notifiable->routes['mail'] === $herring
        );
    }
}
