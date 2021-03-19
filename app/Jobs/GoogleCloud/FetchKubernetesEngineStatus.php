<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchKubernetesEngineStatus extends FetchStatus
{
    protected function getExternalComponentId(): string
    {
        return 'google-kubernetes-engine';
    }
}
