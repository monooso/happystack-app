<?php

declare(strict_types=1);

namespace App\Jobs\GoogleCloud;

final class FetchFirestoreStatus extends FetchStatus
{
    protected function getComponentId(): string
    {
        return 'cloud-firestore';
    }
}
