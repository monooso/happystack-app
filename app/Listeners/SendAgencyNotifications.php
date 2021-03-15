<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatusUpdated;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;
use Illuminate\Notifications\AnonymousNotifiable;

final class SendAgencyNotifications
{
    public function handle(StatusUpdated $event): void
    {
        $component = $event->component;
        $projects = $component->projects()->with('agencyChannels')->get();

        /** @var Project $project */
        foreach ($projects as $project) {
            $channels = $project->agencyChannels()->get();

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

            $notifiable->notify(new AgencyComponentStatusChanged(
                $project,
                $component
            ));
        }
    }
}
