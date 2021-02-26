<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'handle'];

    /**
     * Get the components owned by this service
     *
     * @return HasMany
     */
    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }

    /**
     * Return the logo SVG string
     *
     * @return string
     */
    public function getLogoSvgAttribute(): string
    {
        $logoPath = Storage::path('public/service-logos/' . $this->handle . '.svg');

        return file_exists($logoPath) ? file_get_contents($logoPath) : '';
    }
}
