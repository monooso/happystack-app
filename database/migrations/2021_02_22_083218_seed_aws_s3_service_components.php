<?php

use App\Constants\AwsRegion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedAwsS3ServiceComponents extends Migration
{
    public function up()
    {
        $serviceId = $this->getServiceId();

        if (! $serviceId) {
            throw new Exception('AWS S3 service does not exist');
        }

        $now = now();

        foreach ($this->getComponents() as $handle => $name) {
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
            ->whereIn('handle', array_keys($this->getComponents()))
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

    /**
     * Get an array of all supported components (services and regions)
     *
     * @return array
     */
    private function getComponents(): array
    {
        $regions = AwsRegion::map();

        $keys = array_map(fn ($k) => 'aws-s3::' . $k, array_keys($regions));

        return array_combine($keys, array_values($regions));
    }
}
