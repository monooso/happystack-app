<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the components this project is monitoring
     *
     * @return BelongsToMany
     */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class);
    }

    /**
     * Get the team which owns this project
     *
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
