<?php

declare(strict_types=1);

namespace App\Jobs\Mailgun;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchEmailValidationStatus extends FetchStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'msj99l1ctybs';
    }
}
