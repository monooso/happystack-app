<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Constants\NotificationChannel;
use App\Models\Client;
use App\Models\Component;
use App\Models\Project;
use App\Models\Team;
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
    public function toMailSetsTheRecipient()
    {
        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();
        $notifiable = Client::factory()->for($project)->create();

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertCount(1, $mailable->to);
        $this->assertSame($notifiable->mail_route, $mailable->to[0]['address']);
    }

    /** @test */
    public function toMailSetsTheSender()
    {
        $team = Team::factory()->create();

        $component = Component::factory()->create();

        $project = Project::factory()
            ->for($team)
            ->hasAttached($component)
            ->create();

        $notifiable = Client::factory()->for($project)->create();

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertSame($team->owner->name, $mailable->from[0]['name']);
        $this->assertSame(config('mail.from.address'), $mailable->from[0]['address']);
    }

    /** @test */
    public function toMailSetsTheReplyTo()
    {
        $team = Team::factory()->create();

        $component = Component::factory()->create();

        $project = Project::factory()
            ->for($team)
            ->hasAttached($component)
            ->create();

        $notifiable = Client::factory()->for($project)->create();

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertSame($team->owner->name, $mailable->replyTo[0]['name']);
        $this->assertSame($team->owner->email, $mailable->replyTo[0]['address']);
    }

    /** @test */
    public function toMailSetsTheMessageText()
    {
        $message = $this->faker->sentence();

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
            'via_mail' => false,
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
            'via_mail' => true,
        ]);

        $result = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->via($notifiable);

        $this->assertSame([NotificationChannel::MAIL], $result);
    }
}
