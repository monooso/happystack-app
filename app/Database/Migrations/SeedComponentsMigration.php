<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Exception;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

abstract class SeedComponentsMigration extends Migration
{
    /**
     * Run the migration
     *
     * @throws Exception
     */
    public function up()
    {
        $serviceId = $this->getServiceId();

        if (! $serviceId) {
            $serviceKey = $this->getServiceKey();
            throw new Exception("The '${serviceKey}' service does not exist");
        }

        $now = now();

        foreach ($this->getComponents() as $handle => $name) {
            DB::table('components')->insert([
                'service_id' => $serviceId,
                'name' => $name,
                'handle' => $handle,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migration
     */
    public function down()
    {
        $serviceId = $this->getServiceId();

        if ($serviceId === null) {
            return;
        }

        DB::table('components')
            ->where('service_id', $serviceId)
            ->whereIn('handle', array_keys($this->getComponents()))
            ->delete();
    }

    /**
     * Get the service ID from the database
     *
     * @throws InvalidArgumentException
     */
    protected function getServiceId(): ?int
    {
        return DB::table('services')
            ->where('handle', $this->getServiceKey())
            ->value('id');
    }

    /**
     * Get an associative array of all supported components
     *
     * ['service-key::component-key' => 'Component label']
     */
    abstract protected function getComponents(): array;

    /**
     * Get the service key
     *
     * For example, "aws-sns".
     */
    abstract protected function getServiceKey(): string;
}
