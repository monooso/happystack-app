<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\FetchJobResolutionException;
use Illuminate\Support\Str;

final class FetchJobResolver
{
    /**
     * Get the class name of the "fetch" job associated with the given handle
     *
     * @param string $handle The component handle
     *
     * @return string The fully-qualified class name
     *
     * @throws FetchJobResolutionException
     */
    public static function resolve(string $handle): string
    {
        $parts = explode('::', $handle);

        if (count($parts) !== 2) {
            $message = "Invalid component handle '${handle}'";
            throw new FetchJobResolutionException($message);
        }

        $service = Str::studly($parts[0]);
        $component = Str::studly('fetch-' . $parts[1] . '-status');

        $className = '\\App\\Jobs\\' . $service . '\\' . $component;

        if (!class_exists($className)) {
            $message = "Unable to resolve job class '${className}'";
            throw new FetchJobResolutionException($message);
        }

        return $className;
    }
}
