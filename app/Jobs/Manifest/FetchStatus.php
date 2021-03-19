<?php

namespace App\Jobs\Manifest;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getExternalPageId(): string
    {
        return 'w1q1x1xcst54';
    }
}
