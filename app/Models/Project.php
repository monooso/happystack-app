<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'notification_email',
        'notify_client',
        'client_email',
        'client_message',
    ];

    protected $with = ['components'];

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
