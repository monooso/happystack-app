<?php

namespace App\Models;

use App\Constants\ServiceVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'handle'];

    /**
     * Get the components owned by this service
     */
    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }

    /**
     * Is access to this service restricted?
     */
    public function isRestricted(): bool
    {
        return $this->visibility === ServiceVisibility::RESTRICTED;
    }
}
