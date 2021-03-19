<?php

declare(strict_types=1);

namespace App\Jobs\Sendgrid;

use Illuminate\Contracts\Queue\ShouldQueue;

final class FetchMarketingCampaignsStatus extends FetchStatus implements ShouldQueue
{
    protected function getExternalComponentId(): string
    {
        return '0srpdt598t37';
    }
}
