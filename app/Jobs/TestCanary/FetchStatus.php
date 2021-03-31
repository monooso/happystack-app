<?php

namespace App\Jobs\TestCanary;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getExternalPageId(): string
    {
        return 'w1q1x1xcst54';
    }
}
