<?php

namespace App\Jobs\Digitalocean;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getExternalPageId(): string
    {
        return 's2k7tnzlhrpw';
    }
}
