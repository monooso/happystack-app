<?php

namespace App\Notifications;

use App\Constants\NotificationChannel;
use App\Mail\AgencyComponentStatusChanged as Mailable;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;

class AgencyComponentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Project $project,
        private Component $component
    ) {
    }

    public function toMail(AnonymousNotifiable $notifiable): Mailable
    {
        $address = $notifiable->routeNotificationFor(NotificationChannel::MAIL);

        return (new Mailable($this->project, $this->component))
            ->subject('Happy Stack Status Alert')
            ->to($address);
    }

    public function via(): array
    {
        return [NotificationChannel::MAIL];
    }
}
