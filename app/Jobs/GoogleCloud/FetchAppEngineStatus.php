<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchAppEngineStatus extends FetchStatus
{
    protected function getComponentId(): string
    {
        return 'google-app-engine';
    }
}
