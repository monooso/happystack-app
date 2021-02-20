<?php

namespace App\Events;

use App\Models\Component;
use Illuminate\Foundation\Events\Dispatchable;

class StatusRetrieved
{
    use Dispatchable;

    public Component $component;
    public string $status;

    /**
     * Constructor
     *
     * @param Component $component
     * @param string    $status
     */
    public function __construct(Component $component, string $status)
    {
        $this->component = $component;
        $this->status = $status;
    }
}
