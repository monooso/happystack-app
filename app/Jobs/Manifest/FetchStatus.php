<?php

namespace App\Jobs\Manifest;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getPageId(): string
    {
        return 'w1q1x1xcst54';
    }
}
