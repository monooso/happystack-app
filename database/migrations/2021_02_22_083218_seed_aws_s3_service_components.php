<?php

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
        $regions = [
            'af-south-1'     => 'Africa (Cape Town)',
            'ap-east-1'      => 'Asia Pacific (Hong Kong)',
            'ap-northeast-1' => 'Asia Pacific (Tokyo)',
            'ap-northeast-2' => 'Asia Pacific (Seoul)',
            'ap-northeast-3' => 'Asia Pacific (Osaka-Local)',
            'ap-south-1'     => 'Asia Pacific (Mumbai)',
            'ap-southeast-1' => 'Asia Pacific (Singapore)',
            'ap-southeast-2' => 'Asia Pacific (Sydney)',
            'ca-central-1'   => 'Canada (Central)',
            'cn-north-1'     => 'China (Beijing)',
            'cn-northwest-1' => 'China (Ningxia)',
            'eu-central-1'   => 'Europe (Frankfurt)',
            'eu-south-1'     => 'Europe (Milan)',
            'eu-north-1'     => 'Europe (Stockholm)',
            'eu-west-1'      => 'Europe (Ireland)',
            'eu-west-2'      => 'Europe (London)',
            'eu-west-3'      => 'Europe (Paris)',
            'me-south-1'     => 'Middle East (Bahrain)',
            'sa-east-1'      => 'South America (São Paulo)',
            'us-standard'    => 'US East (N. Virginia)',
            'us-east-2'      => 'US East (Ohio)',
            'us-west-1'      => 'US West (N. California)',
            'us-west-2'      => 'US West (Oregon)',
        ];

        $keys = array_map(fn ($k) => 'aws-s3::' . $k, array_keys($regions));

        return array_combine($keys, array_values($regions));
    }
}
