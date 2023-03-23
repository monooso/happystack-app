<?php

declare(strict_types=1);

namespace App\Support;

class Config
{
    /**
     * Converts a delimited string to an array.
     *
     * Removes leading an trailing whitespace from each array item.
     */
    public static function stringToArray(string $input, string $delimiter = '|'): array
    {
        return collect(explode($delimiter, $input))->map(fn (string $s) => trim($s))->all();
    }
}
