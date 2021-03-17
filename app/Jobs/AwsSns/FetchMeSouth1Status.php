<?php

declare(strict_types=1);

namespace App\Jobs\AwsSns;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchMeSouth1Status extends FetchAwsStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'sns-me-south-1';
    }
}
