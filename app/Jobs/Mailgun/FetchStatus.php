<?php

namespace App\Jobs\Mailgun;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getPageId(): string
    {
        return '6jp439mdyy0k';
    }
}
