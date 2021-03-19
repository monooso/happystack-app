<?php

declare(strict_types=1);

namespace App\Jobs\AwsS3;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchAfSouth1Status extends FetchAwsStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 's3-af-south-1';
    }
}
