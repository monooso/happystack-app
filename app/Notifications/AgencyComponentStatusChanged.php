<?php

namespace App\Notifications;

use App\Constants\NotificationChannel;
use App\Mail\AgencyComponentStatusChanged as Mailable;
use App\Models\Agency;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class AgencyComponentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Project $project,
        public Component $component
    ) {
    }

    public function toMail(Agency $notifiable): Mailable
    {
        return (new Mailable($this->project, $this->component))
            ->to($notifiable->routeNotificationForMail())
            ->subject('Happy Stack Status Alert');
    }

    public function via(Agency $notifiable): array
    {
        return $notifiable->via_mail === true
            ? [NotificationChannel::MAIL]
            : [];
    }
}
