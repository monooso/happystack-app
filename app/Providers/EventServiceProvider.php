<?php

namespace App\Providers;

use App\Events\StatusChanged;
use App\Events\StatusFetched;
use App\Listeners\SendAgencyNotifications;
use App\Listeners\SendClientNotifications;
use App\Listeners\UpdateComponentStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        StatusFetched::class => [UpdateComponentStatus::class],
        StatusChanged::class => [
            SendAgencyNotifications::class,
            SendClientNotifications::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
