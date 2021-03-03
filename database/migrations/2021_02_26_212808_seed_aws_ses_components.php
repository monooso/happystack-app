<?php

use App\Constants\AwsRegion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedAwsSesComponents extends Migration
{
    public function up()
    {
        $serviceId = $this->getServiceId();

        if (! $serviceId) {
            throw new Exception('AWS SES service does not exist');
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
     * Get the AWS SES service ID from the database
     *
     * @return null|int
     *
     * @throws InvalidArgumentException
     */
    private function getServiceId(): ?int
    {
        return DB::table('services')->where('handle', 'aws-ses')->value('id');
    }

    /**
     * Get an array of all supported components (services and regions)
     *
     * @return array
     */
    private function getComponents(): array
    {
        $regions = AwsRegion::map();

        $keys = array_map(fn ($k) => 'aws-ses::' . $k, array_keys($regions));

        return array_combine($keys, array_values($regions));
    }
}
