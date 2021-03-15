<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Constants\NotificationChannel;
use App\Models\ClientChannel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class ClientChannelTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function canReceiveNotificationExcludesRecentlyNotifiedChannels()
    {
        $channel = ClientChannel::factory()->create([
            'last_notified_at' => Carbon::now()->subHours(25),
        ]);

        ClientChannel::factory()->create([
            'last_notified_at' => Carbon::now()->subHours(23),
        ]);

        /** @var Collection $result */
        $result = ClientChannel::canReceiveNotification()->get();

        $this->assertSame(1, $result->count());
        $this->assertSame($channel->id, $result->first()->id);
    }

    /** @test */
    public function canReceiveNotificationIncludesNeverNotifiedChannels()
    {
        $channel = ClientChannel::factory()->create([
            'last_notified_at' => null,
        ]);

        /** @var Collection $result */
        $result = ClientChannel::canReceiveNotification()->get();

        $this->assertSame(1, $result->count());
        $this->assertSame($channel->id, $result->first()->id);
    }

    /** @test */
    public function toMailExcludesNonMailChannels()
    {
        $channel = ClientChannel::factory()->create([
            'type'  => NotificationChannel::MAIL,
            'route' => 'a@b.com',
        ]);

        ClientChannel::factory()->create([
            'type'  => 'sms',
            'route' => $this->faker->phoneNumber,
        ]);

        $result = ClientChannel::toMail()->get();

        $this->assertSame(1, $result->count());
        $this->assertSame($channel->id, $result->first()->id);
    }
}
