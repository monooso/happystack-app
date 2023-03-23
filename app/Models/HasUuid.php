<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Automatically generate a UUID when creating a model
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function (Model $model) {
            if (! $model->uuid) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Use the UUID field for route-model binding
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
