<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Models\ClientChannel;
use App\Models\Component;
use App\Models\Project;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;

final class ClientComponentStatusChangedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function toMailSetsTheSubject()
    {
        $mail = $this->faker->email;
        $component = Component::factory()->create();

        $project = Project::factory()
            ->has(ClientChannel::factory(['type' => 'mail', 'route' => $mail]))
            ->hasAttached($component)
            ->create();

        $notifiable = new AnonymousNotifiable();
        $notifiable->routes = ['mail' => $this->faker->email];

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertSame('Website Status', $mailable->subject);
    }

    /** @test */
    public function toMailSetsTheRecipient()
    {
        $mail = $this->faker->email;
        $component = Component::factory()->create();

        $project = Project::factory()
            ->has(ClientChannel::factory(['type' => 'mail', 'route' => $mail]))
            ->hasAttached($component)
            ->create();

        $notifiable = new AnonymousNotifiable();
        $notifiable->routes = ['mail' => $mail];

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $this->assertTrue($mailable->hasTo($mail));
    }

    /** @test */
    public function toMailSetsTheMessageText()
    {
        $mail = $this->faker->email;
        $message = $this->faker->realText();
        $component = Component::factory()->create();

        $project = Project::factory()
            ->has(ClientChannel::factory([
                'type'    => 'mail',
                'route'   => $mail,
                'message' => $message,
            ]))
            ->hasAttached($component)
            ->create();

        $notifiable = new AnonymousNotifiable();
        $notifiable->routes = ['mail' => $mail];

        $mailable = (new ClientComponentStatusChanged(
            $project,
            $component
        ))->toMail($notifiable);

        $mailable->assertSeeInText($message);
    }
}
