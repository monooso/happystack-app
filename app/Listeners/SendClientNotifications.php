<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatusChanged;
use App\Models\Project;
use App\Notifications\ClientComponentStatusChanged;
use Illuminate\Support\Carbon;

final class SendClientNotifications
{
    public function handle(StatusChanged $event): void
    {
        $component = $event->component;

        // We don't notify clients when a problem is resolved
        if ($component->isHealthy) {
            return;
        }

        $projects = $component->projects()->with('client')->get();

        /** @var Project $project */
        foreach ($projects as $project) {
            $client = $project->client;

            if ($client === null) {
                continue;
            }

            $client->notify(new ClientComponentStatusChanged(
                $project,
                $component
            ));

            // Make a note that the client was notified. This will probably move
            // at some point, but this is the easiest solution for now.
            $client->last_notified_at = Carbon::now();
            $client->save();
        }
    }
}
