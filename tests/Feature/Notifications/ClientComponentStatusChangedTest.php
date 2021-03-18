<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Constants\NotificationChannel;
use App\Models\Client;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class ClientComponentStatusChangedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function toMailSetsTheSubject()
    {
        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();
        $notifiable = Client::factory()->for($project)->create();

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertSame('Website Status', $mailable->subject);
    }

    /** @test */
    public function toMailSetsTheMessageText()
    {
        $message = $this->faker->realText();

        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();

        $notifiable = Client::factory()->for($project)->create([
            'mail_message' => $message,
        ]);

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $mailable->assertSeeInText($message);
    }

    /** @test */
    public function viaReturnsAnEmptyArrayIfCanBeNotifiedIsFalse()
    {
        $project = Project::factory()->make();
        $component = Component::factory()->make();

        $notifiable = Client::factory()->make(['notified_at' => Carbon::now()]);

        $result = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->via($notifiable);

        $this->assertSame([], $result);
    }

    /** @test */
    public function viaReturnsAnEmptyArrayIfViaMailIsFalse()
    {
        $project = Project::factory()->make();
        $component = Component::factory()->make();

        $notifiable = Client::factory()->make([
            'notified_at' => null,
            'via_mail'    => false,
        ]);

        $result = (new ClientComponentStatusChanged(
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

        $notifiable = Client::factory()->make([
            'notified_at' => null,
            'via_mail'    => true,
        ]);

        $result = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->via($notifiable);

        $this->assertSame([NotificationChannel::MAIL], $result);
    }
}
