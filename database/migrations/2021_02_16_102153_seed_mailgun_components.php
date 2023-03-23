<?php

declare(strict_types=1);

use App\Database\Migrations\SeedComponentsMigration;

return new class extends SeedComponentsMigration
{
    protected function getComponents(): array
    {
        return [
            'mailgun::api' => 'API',
            'mailgun::control-panel' => 'Control Panel',
            'mailgun::email-validation' => 'Email Validation',
            'mailgun::events-logs' => 'Events and Logs',
            'mailgun::inbound-email-processing' => 'Inbound Email Processing',
            'mailgun::inbox-placement' => 'Inbox Placement',
            'mailgun::outbound-delivery' => 'Outbound Delivery',
            'mailgun::smtp' => 'SMTP',
        ];
    }

    protected function getServiceKey(): string
    {
        return 'mailgun';
    }
};
