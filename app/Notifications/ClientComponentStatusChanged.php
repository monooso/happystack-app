<?php

namespace App\Notifications;

use App\Constants\NotificationChannel;
use App\Mail\ClientComponentStatusChanged as Mailable;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ClientComponentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Project $project,
        public Component $component
    ) {
    }

    public function toMail($notifiable): Mailable
    {
        $message = $notifiable->mail_message ?? '';

        return (new Mailable($message))->subject('Website Status');
    }

    public function via($notifiable): array
    {
        if ($notifiable->can_be_notified === false) {
            return [];
        }

        return ($notifiable->via_mail === true)
            ? [NotificationChannel::MAIL]
            : [];
    }
}
