<?php

declare(strict_types=1);

use App\Support\Config;

return [
    // How frequently we'll update the statuses, in seconds
    'status_refresh_interval' => env('STATUS_REFRESH_INTERVAL', 300),
    'super_users' => Config::stringToArray(env('SUPER_USERS', '')),
];
