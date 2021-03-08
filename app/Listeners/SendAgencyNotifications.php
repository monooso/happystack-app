<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatusUpdated;
use App\Mail\AgencyComponentStatusChanged;
use Illuminate\Support\Facades\Mail;

final class SendAgencyNotifications
{
    public function handle(StatusUpdated $event): void
    {
        $component = $event->component;

        $projects = $component->projects()->get();

        foreach ($projects as $project) {
            $mailable = new AgencyComponentStatusChanged($project, $component);
            Mail::to($project->notification_email)->send($mailable);
        }
    }
}
