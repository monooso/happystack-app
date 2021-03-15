<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Models\Component;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;

final class AgencyComponentStatusChangedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itSetsTheSubject()
    {
        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();

        $notifiable = new AnonymousNotifiable();
        $notifiable->routes = ['mail' => $this->faker->email];

        $mailable = (new AgencyComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertSame('Happy Stack Status Alert', $mailable->subject);
    }

    /** @test */
    public function itSetsTheRecipient()
    {
        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();

        $recipient = $this->faker->email;
        $notifiable = new AnonymousNotifiable();
        $notifiable->routes = ['mail' => $recipient];

        $mailable = (new AgencyComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertTrue($mailable->hasTo($recipient));
    }
}
