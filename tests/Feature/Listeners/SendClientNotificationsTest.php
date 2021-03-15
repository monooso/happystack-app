<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Constants\Status;
use App\Events\StatusUpdated;
use App\Listeners\SendClientNotifications;
use App\Models\AgencyChannel;
use App\Models\ClientChannel;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Carbon;
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

        $component = Component::factory()->create([
            'current_status' => Status::DOWN,
        ]);

        Project::factory()->count(3)
            ->has(ClientChannel::factory())
            ->hasAttached($component)
            ->create();

        (new SendClientNotifications())->handle(new StatusUpdated($component));

        Notification::assertSentToTimes(
            new AnonymousNotifiable(),
            ClientComponentStatusChanged::class,
            3
        );
    }

    /** @test */
    public function itSendsTheEmailToTheClientChannel()
    {
        Notification::fake();

        $address = $this->faker->unique()->email;
        $herring = $this->faker->unique()->email;

        $component = Component::factory()->create([
            'current_status' => Status::WARN,
        ]);

        Project::factory()
            ->has(ClientChannel::factory(['type' => 'mail', 'route' => $address]))
            ->has(AgencyChannel::factory(['type' => 'mail', 'route' => $herring]))
            ->hasAttached($component)
            ->create();

        (new SendClientNotifications())->handle(new StatusUpdated($component));

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            ClientComponentStatusChanged::class,
            fn ($a, $b, $notifiable) => $notifiable->routes['mail'] === $address
        );

        Notification::assertNotSentTo(
            new AnonymousNotifiable(),
            ClientComponentStatusChanged::class,
            fn ($a, $b, $notifiable) => $notifiable->routes['mail'] === $herring
        );
    }

    /** @test */
    public function itDoesNotSendNotificationsIfTheComponentStatusIsOkay()
    {
        Notification::fake();

        $component = Component::factory()->create([
            'current_status' => Status::OKAY,
        ]);

        Project::factory()
            ->has(ClientChannel::factory(['type' => 'mail', 'route' => 'a@b.com']))
            ->hasAttached($component)
            ->create();

        (new SendClientNotifications())->handle(new StatusUpdated($component));

        Notification::assertNothingSent();
    }

    /** @test */
    public function itSendsAMaximumOfOneNotificationPerDay()
    {
        Notification::fake();

        $component = Component::factory()->create();

        Project::factory()
            ->has(ClientChannel::factory([
                'type'             => 'mail',
                'last_notified_at' => Carbon::now()->subHours(23),
            ]))
            ->hasAttached($component)
            ->create();

        (new SendClientNotifications())->handle(new StatusUpdated($component));

        Notification::assertNothingSent();
    }
}
