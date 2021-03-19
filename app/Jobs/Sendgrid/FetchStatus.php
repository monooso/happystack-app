<?php

namespace App\Jobs\Sendgrid;

use App\Jobs\FetchStatusPageStatus;

abstract class FetchStatus extends FetchStatusPageStatus
{
    protected function getExternalPageId(): string
    {
        return '3tgl2vf85cht';
    }
}
