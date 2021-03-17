<?php

declare(strict_types=1);

namespace App\Jobs\AwsSqs;

use App\Jobs\FetchAwsStatus;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchApNortheast3Status extends FetchAwsStatus implements ShouldQueue, ShouldBeUnique
{
    protected function getComponentId(): string
    {
        return 'sqs-ap-northeast-3';
    }
}
