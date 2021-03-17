<?php

declare(strict_types=1);

namespace App\Jobs\AwsS3;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchUsWest1Status extends FetchAwsStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 's3-us-west-1';
    }
}
