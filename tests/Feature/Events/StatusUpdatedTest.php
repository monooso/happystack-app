<?php

declare(strict_types=1);

namespace Tests\Feature\Events;

use App\Constants\Status;
use App\Events\StatusUpdated;
use App\Models\AgencyChannel;
use App\Models\ClientChannel;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class StatusUpdatedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function aStatusUpdatedEventSendsAgencyNotifications()
    {
        Notification::fake();

        $mail = $this->faker->unique()->email;

        $component = Component::factory()->create();

        Project::factory()
            ->has(AgencyChannel::factory(['type' => 'mail', 'route' => $mail]))
            ->hasAttached($component)
            ->create();

        StatusUpdated::dispatch($component);

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            AgencyComponentStatusChanged::class,
            fn ($a, $b, $notifiable) => $notifiable->routes['mail'] === $mail
        );
    }

    /** @test */
    public function aStatusUpdatedEventSendsClientNotifications()
    {
        Notification::fake();

        $mail = $this->faker->unique()->email;

        $component = Component::factory()->create([
            'current_status' => Status::DOWN,
        ]);

        Project::factory()
            ->has(ClientChannel::factory(['type' => 'mail', 'route' => $mail]))
            ->hasAttached($component)
            ->create();

        StatusUpdated::dispatch($component);

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            ClientComponentStatusChanged::class,
            fn ($a, $b, $notifiable) => $notifiable->routes['mail'] === $mail
        );
    }
}
