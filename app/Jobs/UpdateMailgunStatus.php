<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMailgunStatus extends UpdateStatusPageStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getPageId(): string
    {
        return '6jp439mdyy0k';
    }

    protected function getServiceKey(): string
    {
        return 'mailgun';
    }
}
