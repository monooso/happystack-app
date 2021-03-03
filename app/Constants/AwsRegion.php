<?php

declare(strict_types=1);

namespace App\Constants;

use Illuminate\Support\Facades\Lang;
use ReflectionClass;

abstract class AwsRegion
{
    public const AF_SOUTH_1 = 'af-south-1';
    public const AP_EAST_1 = 'ap-east-1';
    public const AP_NORTHEAST_1 = 'ap-northeast-1';
    public const AP_NORTHEAST_2 = 'ap-northeast-2';
    public const AP_NORTHEAST_3 = 'ap-northeast-3';
    public const AP_SOUTH_1 = 'ap-south-1';
    public const AP_SOUTHEAST_1 = 'ap-southeast-1';
    public const AP_SOUTHEAST_2 = 'ap-southeast-2';
    public const CA_CENTRAL_1 = 'ca-central-1';
    public const CN_NORTH_1 = 'cn-north-1';
    public const CN_NORTHWEST_1 = 'cn-northwest-1';
    public const EU_CENTRAL_1 = 'eu-central-1';
    public const EU_SOUTH_1 = 'eu-south-1';
    public const EU_NORTH_1 = 'eu-north-1';
    public const EU_WEST_1 = 'eu-west-1';
    public const EU_WEST_2 = 'eu-west-2';
    public const EU_WEST_3 = 'eu-west-3';
    public const ME_SOUTH_1 = 'me-south-1';
    public const SA_EAST_1 = 'sa-east-1';
    public const US_EAST_1 = 'us-east-1';
    public const US_EAST_2 = 'us-east-2';
    public const US_WEST_1 = 'us-west-1';
    public const US_WEST_2 = 'us-west-2';

    /**
     * Get an array of all AWS region keys
     *
     * @return string[]
     */
    public static function all(): array
    {
        $reflect = new ReflectionClass(self::class);
        $constants = $reflect->getReflectionConstants();

        return array_map(fn ($constant) => $constant->getValue(), $constants);
    }

    /**
     * Get a map of AWS region keys to human-friendly name
     *
     * @return array
     */
    public static function map(): array
    {
        $keys = self::all();
        $map = [];

        foreach ($keys as $key) {
            $map[$key] = Lang::get('aws.' . $key);
        }

        return $map;
    }
}
