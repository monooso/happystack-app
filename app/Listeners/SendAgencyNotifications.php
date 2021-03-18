<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatusChanged;
use App\Models\Project;
use App\Notifications\AgencyComponentStatusChanged;

final class SendAgencyNotifications
{
    public function handle(StatusChanged $event): void
    {
        $component = $event->component;
        $projects = $component->projects()->with('agency')->get();

        /** @var Project $project */
        foreach ($projects as $project) {
            $agency = $project->agency;

            if ($agency === null) {
                continue;
            }

            $agency->notify(new AgencyComponentStatusChanged(
                $project,
                $component
            ));
        }
    }
}
