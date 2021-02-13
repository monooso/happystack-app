<?php

namespace App\Providers;

use App\Events\StatusRetrieved;
use App\Listeners\UpdateComponentStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class      => [SendEmailVerificationNotification::class],
        StatusRetrieved::class => [UpdateComponentStatus::class],
    ];
}
