<?php

declare(strict_types=1);

namespace App\Jobs\Mailgun;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchSmtpStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    public function getComponentId(): string
    {
        return 'pdjtfylt9rv1';
    }
}
