<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class AgencyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function routeNotificationForMailReturnsTheMailRoutePropertyIfSet()
    {
        $email = $this->faker->email();
        $client = Agency::factory()->make(['mail_route' => $email]);

        $this->assertSame($email, $client->routeNotificationForMail());
    }

    /** @test */
    public function routeNotificationForMailReturnsAnEmptyStringIfMailRouteIsNull()
    {
        $client = Agency::factory()->make(['mail_route' => null]);

        $this->assertSame('', $client->routeNotificationForMail());
    }
}
