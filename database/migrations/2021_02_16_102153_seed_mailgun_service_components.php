<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedMailgunServiceComponents extends Migration
{
    private array $components = [
        'api'                      => 'API',
        'control_panel'            => 'Control Panel',
        'email_validation'         => 'Email Validation',
        'events_logs'              => 'Events and Logs',
        'inbound_email_processing' => 'Inbound Email Processing',
        'inbox_placement'          => 'Inbox Placement',
        'outbound_delivery'        => 'Outbound Delivery',
        'smtp'                     => 'SMTP',
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
        return DB::table('services')->whereHandle('mailgun')->value('id');
    }
}
