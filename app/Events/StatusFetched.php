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

    public function __construct(public Component $component, public string $status)
    {
    }
}
