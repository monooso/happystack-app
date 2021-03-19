<?php

declare(strict_types=1);

namespace App\Jobs\AwsS3;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchCnNorthwest1Status extends FetchAwsStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return 's3-cn-northwest-1';
    }
}
