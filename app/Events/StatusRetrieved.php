<?php

namespace App\Events;

use App\PlainObjects\StatusUpdate;
use Illuminate\Foundation\Events\Dispatchable;

class StatusRetrieved
{
    use Dispatchable;

    public StatusUpdate $statusUpdate;

    /**
     * Constructor
     *
     * @param StatusUpdate $statusUpdate
     */
    public function __construct(StatusUpdate $statusUpdate)
    {
        $this->statusUpdate = $statusUpdate;
    }
}
