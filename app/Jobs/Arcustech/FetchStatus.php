<?php

namespace App\Jobs\Arcustech;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getPageId(): string
    {
        return '35gfybn5xr1b';
    }
}
