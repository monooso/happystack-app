<?php

namespace App\Jobs\Digitalocean;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getPageId(): string
    {
        return 's2k7tnzlhrpw';
    }
}
