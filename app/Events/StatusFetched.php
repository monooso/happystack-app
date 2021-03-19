<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Component;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class StatusFetched
{
    use Dispatchable;
    use SerializesModels;

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
