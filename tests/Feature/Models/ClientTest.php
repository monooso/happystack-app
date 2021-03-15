<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
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
        $client = Client::factory()->make(['last_notified_at' => null]);

        $this->assertTrue($client->canBeNotified);
    }

    /** @test */
    public function canBeNotifiedIsTrueIfTheClientWasNotifiedMoreThanOneDayAgo()
    {
        $client = Client::factory()->make([
            'last_notified_at' => Carbon::now()->subHours(24),
        ]);

        $this->assertTrue($client->canBeNotified);
    }

    /** @test */
    public function canBeNotifiedIsFalseIfTheClientWasNotifiedWithinOneDay()
    {
        $client = Client::factory()->make([
            'last_notified_at' => Carbon::now()->subHours(23),
        ]);

        $this->assertFalse($client->canBeNotified);
    }
}
