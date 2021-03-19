<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchWorkflowsStatus extends FetchStatus
{
    protected function getExternalComponentId(): string
    {
        return 'cloud-workflows';
    }
}
