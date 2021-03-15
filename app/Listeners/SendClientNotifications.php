<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Constants\Status;
use App\Events\StatusUpdated;
use App\Models\Project;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Notifications\AnonymousNotifiable;

final class SendClientNotifications
{
    public function handle(StatusUpdated $event): void
    {
        $component = $event->component;

        // We don't notify clients when a problem is resolved
        if ($component->current_status === Status::OKAY) {
            return;
        }

        $projects = $component->projects()->with('clientChannels')->get();

        /** @var Project $project */
        foreach ($projects as $project) {
            $channels = $project->clientChannels()->canReceiveNotification()->get();

            if ($channels->count() === 0) {
                continue;
            }

            /** @var AnonymousNotifiable $notifiable */
            $notifiable = $channels->reduce(
                fn ($notifiable, $channel) => $notifiable->route(
                    $channel->type,
                    $channel->route
                ),
                new AnonymousNotifiable()
            );

            $notifiable->notify(new ClientComponentStatusChanged(
                $project,
                $component
            ));
        }
    }
}
