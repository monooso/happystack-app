<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Client;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

final class ClientTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function routeNotificationForMailReturnsTheMailRoutePropertyIfSet()
    {
        $email = $this->faker->email;
        $client = Client::factory()->make(['mail_route' => $email]);

        $this->assertSame($email, $client->routeNotificationForMail());
    }

    /** @test */
    public function routeNotificationForMailReturnsAnEmptyStringIfMailRouteIsNull()
    {
        $client = Client::factory()->make(['mail_route' => null]);

        $this->assertSame('', $client->routeNotificationForMail());
    }

    /** @test */
    public function canBeNotifiedIsTrueIfTheClientHasNeverBeenNotified()
    {
        $client = Client::factory()->make(['notified_at' => null]);

        $this->assertTrue($client->canBeNotified);
    }

    /** @test */
    public function canBeNotifiedIsTrueIfTheClientWasNotifiedMoreThanOneDayAgo()
    {
        $client = Client::factory()->make([
            'notified_at' => Carbon::now()->subHours(24),
        ]);

        $this->assertTrue($client->canBeNotified);
    }

    /** @test */
    public function canBeNotifiedIsFalseIfTheClientWasNotifiedWithinOneDay()
    {
        $client = Client::factory()->make([
            'notified_at' => Carbon::now()->subHours(23),
        ]);

        $this->assertFalse($client->canBeNotified);
    }

    /** @test */
    public function notifyCallsTheDispatcher()
    {
        $client = Client::factory()->create();
        $notifiable = $this->faker->word;

        $this->mock(
            Dispatcher::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('send')
                ->with($client, $notifiable)
                ->once()
        );

        $client->notify($notifiable);
    }

    /** @test */
    public function notifyUpdatesTheNotifiedAtTimestamp()
    {
        $this->travel(2)->days();

        $this->mock(Dispatcher::class, fn ($mock) => $mock->shouldReceive('send'));

        $client = Client::factory()->create(['notified_at' => null]);

        $client->notify(null);

        $this->assertDatabaseHas('clients', [
            'id'          => $client->id,
            'notified_at' => Carbon::now(),
        ]);

        $this->travelBack();
    }

    /** @test */
    public function notifyNowCallsTheDispatcher()
    {
        $client = Client::factory()->create();
        $notifiable = $this->faker->word;
        $channels = $this->faker->words();

        $this->mock(
            Dispatcher::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('sendNow')
                ->with($client, $notifiable, $channels)
                ->once()
        );

        $client->notifyNow($notifiable, $channels);
    }

    /** @test */
    public function notifyNowUpdatesTheNotifiedAtTimestamp()
    {
        $this->travel(2)->days();

        $this->mock(
            Dispatcher::class,
            fn ($mock) => $mock->shouldReceive('sendNow')
        );

        $client = Client::factory()->create(['notified_at' => null]);

        $client->notifyNow(null);

        $this->assertDatabaseHas('clients', [
            'id'          => $client->id,
            'notified_at' => Carbon::now(),
        ]);

        $this->travelBack();
    }
}
