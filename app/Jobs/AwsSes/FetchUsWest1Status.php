<?php

declare(strict_types=1);

namespace App\Jobs\AwsSes;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchUsWest1Status extends FetchAwsStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'ses-us-west-1';
    }
}
