<?php

declare(strict_types=1);

namespace App\Jobs\AwsSes;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchApEast1Status extends FetchAwsStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'ses-ap-east-1';
    }
}
