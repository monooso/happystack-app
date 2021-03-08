<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Events\StatusUpdated;
use App\Listeners\SendAgencyNotifications;
use App\Mail\AgencyComponentStatusChanged;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class SendAgencyNotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itSendsAnEmailForEachAffectedProject()
    {
        Mail::fake();

        $component = Component::factory()->create();
        Project::factory()->count(3)->hasAttached($component)->create();

        (new SendAgencyNotifications())->handle(new StatusUpdated($component));

        Mail::assertSent(AgencyComponentStatusChanged::class, 3);
    }

    /** @test */
    public function itSendsTheEmailToTheProjectNotificationEmail()
    {
        Mail::fake();

        $component = Component::factory()->create();
        $project = Project::factory()->hasAttached($component)->create();

        (new SendAgencyNotifications())->handle(new StatusUpdated($component));

        Mail::assertSent(
            AgencyComponentStatusChanged::class,
            fn ($mail) => $mail->hasTo($project->notification_email)
        );
    }

    /** @test */
    public function eachEmailHasASingleRecipient()
    {
        Mail::fake();

        $component = Component::factory()->create();
        $projects = Project::factory()->count(2)->hasAttached($component)->create();

        (new SendAgencyNotifications())->handle(new StatusUpdated($component));

        Mail::assertSent(
            AgencyComponentStatusChanged::class,
            function ($mail) use ($projects) {
                return $mail->hasTo($projects[0]->notification_email)
                    && !$mail->hasTo($projects[1]->notification_email);
            }
        );

        Mail::assertSent(
            AgencyComponentStatusChanged::class,
            function ($mail) use ($projects) {
                return $mail->hasTo($projects[1]->notification_email)
                    && !$mail->hasTo($projects[0]->notification_email);
            }
        );
    }
}
