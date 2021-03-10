<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $with = ['components'];

    /**
     * Get the agency notification channels for this project
     *
     * @return HasMany
     */
    public function agencyChannels(): HasMany
    {
        return $this->hasMany(AgencyChannel::class);
    }

    /**
     * Get the client notification channels for this project
     *
     * @return HasMany
     */
    public function clientChannels(): HasMany
    {
        return $this->hasMany(ClientChannel::class);
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

    public function getCurrentStatusAttribute(): string
    {
        if ($this->hasComponentErrors) {
            return Status::DOWN;
        }

        if ($this->hasComponentWarnings) {
            return Status::WARN;
        }

        return Status::OKAY;
    }

    public function getHasComponentErrorsAttribute(): bool
    {
        return $this->componentsWithErrors->count() > 0;
    }

    public function getHasComponentWarningsAttribute(): bool
    {
        return $this->componentsWithWarnings->count() > 0;
    }

    public function getUpdatedAtForHumansAttribute(): string
    {
        return $this->updated_at->diffForHumans();
    }

    public function getComponentsWithWarningsAttribute(): Collection
    {
        return $this->components()->where('current_status', Status::WARN)->get();
    }

    public function getComponentsWithErrorsAttribute(): Collection
    {
        return $this->components()->where('current_status', Status::DOWN)->get();
    }
}
