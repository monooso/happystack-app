<?php

namespace App\Notifications;

use App\Constants\NotificationChannel;
use App\Mail\ClientComponentStatusChanged as Mailable;
use App\Models\Client;
use App\Models\Component;
use App\Models\Project;
use App\Models\User;
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

    public function toMail(Client $notifiable): Mailable
    {
        $message = $notifiable->mail_message ?? '';

        /** @var User $sender */
        $sender = $this->project->team->owner;

        return (new Mailable($message))
            ->from(config('mail.from.address'), $sender->name)
            ->replyTo($sender->email, $sender->name)
            ->to($notifiable->routeNotificationForMail())
            ->subject('Website Status');
    }

    public function via(Client $notifiable): array
    {
        if ($notifiable->canBeNotified() === false) {
            return [];
        }

        return ($notifiable->via_mail === true)
            ? [NotificationChannel::MAIL]
            : [];
    }
}
