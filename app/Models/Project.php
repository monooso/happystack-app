<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Project
 *
 * @package App\Models
 *
 * @property-read Agency $agency
 * @property-read Client $client
 * @property-read Team $team
 */
class Project extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = ['name'];

    protected $with = ['components'];

    /**
     * Get the project agency
     *
     * @return HasOne
     */
    public function agency(): HasOne
    {
        return $this->hasOne(Agency::class);
    }

    /**
     * Get the project client
     *
     * @return HasOne
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

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

    /**
     * Get the project status
     *
     * The project status is the same as the "worst" component status.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        $hasWarning = false;

        foreach ($this->components as $component) {
            if ($component->status === Status::DOWN) {
                return Status::DOWN;
            }

            if ($component->status === Status::WARN) {
                $hasWarning = true;
            }
        }

        return $hasWarning ? Status::WARN : Status::OKAY;
    }
}
