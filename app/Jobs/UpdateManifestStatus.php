<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateManifestStatus extends UpdateStatusPageStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getPageId(): string
    {
        return 'w1q1x1xcst54';
    }

    protected function getServiceKey(): string
    {
        return 'manifest';
    }
}
