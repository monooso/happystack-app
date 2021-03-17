<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Constants\NotificationChannel;
use App\Models\Agency;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class AgencyComponentStatusChangedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function toMailSetsTheSubject()
    {
        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();

        $mailable = (new AgencyComponentStatusChanged(
            $project,
            $component
        ))->toMail();

        $this->assertSame('Happy Stack Status Alert', $mailable->subject);
    }

    /** @test */
    public function viaReturnsAnEmptyArrayIfViaMailIsFalse()
    {
        $project = Project::factory()->make();
        $component = Component::factory()->make();
        $notifiable = Agency::factory()->make(['via_mail' => false]);

        $result = (new AgencyComponentStatusChanged(
            $project,
            $component
        ))->via($notifiable);

        $this->assertSame([], $result);
    }

    /** @test */
    public function viaReturnsAnArrayContainingMailIfViaMailIsTrue()
    {
        $project = Project::factory()->make();
        $component = Component::factory()->make();
        $notifiable = Agency::factory()->make(['via_mail' => true]);

        $result = (new AgencyComponentStatusChanged(
            $project,
            $component
        ))->via($notifiable);

        $this->assertSame([NotificationChannel::MAIL], $result);
    }
}
