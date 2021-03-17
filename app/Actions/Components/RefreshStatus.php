<?php

declare(strict_types=1);

namespace App\Actions\Components;

use App\Models\Component;
use Illuminate\Support\Str;

final class RefreshStatus
{
    public function refresh(Component $component)
    {
    }

    public function resolveJobClass(string $componentHandle): ?string
    {
        $parts = explode('::', $componentHandle);

        if (count($parts) !== 2) {
            return null;
        }

        $parts = explode('::', $componentHandle);

        $service = $this->toPascal($parts[0]);
        $component = 'Fetch' . $this->toPascal($parts[1]) . 'Status';

        $className = '\\App\\Jobs\\' . $service . '\\' . $component;

        return class_exists($className) ? $className : null;
    }

    /**
     * Convert a string to PascalCase
     *
     * @param string $input
     *
     * @return string
     */
    private function toPascal(string $input): string
    {
        return ucfirst(Str::camel($input));
    }
}
