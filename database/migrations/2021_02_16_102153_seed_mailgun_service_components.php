<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

final class SeedMailgunServiceComponents extends Migration
{
    private array $components = [
        'mailgun::api'                      => 'API',
        'mailgun::control-panel'            => 'Control Panel',
        'mailgun::email-validation'         => 'Email Validation',
        'mailgun::events-logs'              => 'Events and Logs',
        'mailgun::inbound-email-processing' => 'Inbound Email Processing',
        'mailgun::inbox-placement'          => 'Inbox Placement',
        'mailgun::outbound-delivery'        => 'Outbound Delivery',
        'mailgun::smtp'                     => 'SMTP',
    ];

    public function up()
    {
        $serviceId = $this->getServiceId();

        if (!$serviceId) {
            throw new Exception('Mailgun service does not exist');
        }

        $now = now();

        foreach ($this->components as $handle => $name) {
            DB::table('components')->insert([
                'service_id' => $serviceId,
                'name'       => $name,
                'handle'     => $handle,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        $serviceId = $this->getServiceId();

        if ($serviceId === null) {
            return;
        }

        DB::table('components')
            ->where('service_id', $serviceId)
            ->whereIn('handle', array_keys($this->components))
            ->delete();
    }

    /**
     * Get the Mailgun service ID from the database
     *
     * @return null|int
     *
     * @throws InvalidArgumentException
     */
    private function getServiceId(): ?int
    {
        return DB::table('services')->where('handle', 'mailgun')->value('id');
    }
}
