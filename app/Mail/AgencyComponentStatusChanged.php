<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Component;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class AgencyComponentStatusChanged extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Project $project,
        public Component $component
    ) {
    }

    public function build()
    {
        $component = $this->component;
        $project = $this->project;
        $service = $component->service;

        return $this->markdown('emails.status.agency', [
            'project' => $project->name,
            'service' => $service->name . ' ' . $component->name,
            'status'  => $component->status,
        ]);
    }
}
