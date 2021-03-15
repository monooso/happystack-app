<?php

namespace App\Notifications;

use App\Constants\NotificationChannel;
use App\Mail\ClientComponentStatusChanged as Mailable;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;

class ClientComponentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Project $project,
        private Component $component
    ) {
    }

    public function toMail(AnonymousNotifiable $notifiable): Mailable
    {
        // This should never fail. If it does, make it obvious.
        $channel = $this->project->clientChannels()->toMail()->firstOrFail();

        $address = $notifiable->routeNotificationFor(NotificationChannel::MAIL);

        return (new Mailable($channel->message))
            ->subject('Website Status')
            ->to($address);
    }

    public function via(): array
    {
        return [NotificationChannel::MAIL];
    }
}
