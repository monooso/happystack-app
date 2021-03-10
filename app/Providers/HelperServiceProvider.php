<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\ArrayHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

final class HelperServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Arr::macro('flattenAssoc', [ArrayHelper::class, 'flattenAssoc']);
    }
}
