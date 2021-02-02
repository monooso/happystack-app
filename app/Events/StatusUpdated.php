<?php

namespace App\Events;

use App\Models\StatusUpdate;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusUpdated
{
    use Dispatchable;
    use SerializesModels;

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
