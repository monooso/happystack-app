<?php

declare(strict_types=1);

use App\Database\Migrations\SeedComponentsMigration;

return new class extends SeedComponentsMigration
{
    protected function getComponents(): array
    {
        return [
            'sendgrid::api' => 'API',
            'sendgrid::api-v2' => 'API v2',
            'sendgrid::api-v3' => 'API v3',
            'sendgrid::email-activity' => 'Email Activity',
            'sendgrid::event-webhook' => 'Event Webhook',
            'sendgrid::marketing-campaigns' => 'Marketing Campaigns',
            'sendgrid::parse-api' => 'Parse API',
            'sendgrid::partners' => 'Partners',
            'sendgrid::smtp' => 'SMTP',
            'sendgrid::website' => 'Website',
        ];
    }

    protected function getServiceKey(): string
    {
        return 'sendgrid';
    }
};
