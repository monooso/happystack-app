<?php

declare(strict_types=1);

namespace App\Jobs\AwsSns;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchCnNorth1Status extends FetchAwsStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 'sns-cn-north-1';
    }
}
