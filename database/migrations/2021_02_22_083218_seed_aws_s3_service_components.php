<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedAwsS3ServiceComponents extends Migration
{
    private array $components = [
        's3-us-standard' => 'North Virginia',
        's3-us-east-2'   => 'Ohio',
        's3-us-west-1'   => 'North California',
    ];

    public function up()
    {
        $serviceId = $this->getServiceId();

        if (!$serviceId) {
            throw new Exception('AWS S3 service does not exist');
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
     * Get the AWS S3 service ID from the database
     *
     * @return null|int
     *
     * @throws InvalidArgumentException
     */
    private function getServiceId(): ?int
    {
        return DB::table('services')->where('handle', 'aws-s3')->value('id');
    }
}
