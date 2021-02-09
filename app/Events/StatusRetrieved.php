<?php

namespace App\Events;

use App\PlainObjects\ComponentStatus;
use Illuminate\Foundation\Events\Dispatchable;

class StatusRetrieved
{
    use Dispatchable;

    public ComponentStatus $statusUpdate;

    /**
     * Constructor
     *
     * @param ComponentStatus $statusUpdate
     */
    public function __construct(ComponentStatus $statusUpdate)
    {
        $this->statusUpdate = $statusUpdate;
    }
}
