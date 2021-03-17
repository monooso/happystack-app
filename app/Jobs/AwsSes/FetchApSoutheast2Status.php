<?php

declare(strict_types=1);

namespace App\Jobs\AwsSes;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchApSoutheast2Status extends FetchAwsStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'ses-ap-southeast-2';
    }
}
