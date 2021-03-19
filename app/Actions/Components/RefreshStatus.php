<?php

declare(strict_types=1);

namespace App\Actions\Components;

use App\Jobs\FetchJobResolver;
use App\Models\Component;

final class RefreshStatus
{
    public function refresh(Component $component)
    {
        $jobClass = FetchJobResolver::resolve($component->handle);

        $jobClass::dispatch($component);
    }
}
