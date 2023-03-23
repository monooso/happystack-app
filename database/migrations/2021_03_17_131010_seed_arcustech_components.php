<?php

declare(strict_types=1);

use App\Database\Migrations\SeedComponentsMigration;

return new class extends SeedComponentsMigration
{
    protected function getComponents(): array
    {
        return [
            'arcustech::account-portal' => 'Account Portal',
            'arcustech::customer-support' => 'Customer Support Systems',
            'arcustech::v1-platform-us-central' => 'VPS Platform v1 (US Central)',
            'arcustech::v2-platform-us-central' => 'VPS Platform v2 (US Central)',
            'arcustech::v2-platform-us-west' => 'VPS Platform v2 (US West)',
            'arcustech::v2-platform-eu-nl' => 'VPS Platform v2 (EU Netherlands)',
        ];
    }

    protected function getServiceKey(): string
    {
        return 'arcustech';
    }
};
