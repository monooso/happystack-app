<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;

final class ArrayHelper
{
    /**
     * Recursively an associative array
     *
     * Example:
     * ['name' => ['required' => 'The name is required']]
     * ['name.required' => 'The name is required']
     *
     * @param array  $array
     *
     * @return array
     */
    public static function flattenAssoc(array $array): array
    {
        return self::recursivelyFlattenAssoc($array);
    }

    /**
     * Recursive element of flattenAssoc
     *
     * @param array  $array
     * @param string $prefix
     *
     * @return array
     */
    private static function recursivelyFlattenAssoc(
        array $array,
        string $prefix = ''
    ): array {
        if (! Arr::isAssoc($array)) {
            return $array;
        }

        $flattened = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && Arr::isAssoc($value)) {
                $prefix = $prefix ? $prefix . '.' . $key : $key;

                $flattened = array_merge(
                    $flattened,
                    self::recursivelyFlattenAssoc($value, $prefix)
                );

                continue;
            }

            $flattened[$prefix . '.' . $key] = $value;
        }

        return $flattened;
    }
}
