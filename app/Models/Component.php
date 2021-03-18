<?php

declare(strict_types=1);

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * Component model
 *
 * @package App\Models
 *
 * @property-read bool isHealthy
 */
final class Component extends Model
{
    use HasFactory;

    protected $dates = ['status_updated_at'];

    protected $fillable = ['handle', 'name', 'status', 'status_updated_at'];

    protected $touches = ['projects'];

    /**
     * Get the projects which are monitoring this component
     *
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Get the service to which this component belongs
     *
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the component status updates
     *
     * @return HasMany
     */
    public function statusUpdates(): HasMany
    {
        return $this->hasMany(StatusUpdate::class);
    }

    /**
     * Update the component status
     *
     * @todo wrap updates in a database transaction
     *
     * @param string $status
     */
    public function updateStatus(string $status)
    {
        // Update the status history
        $this->statusUpdates()->create(['status' => $status]);

        // Update the component
        $this->status = $status;
        $this->status_updated_at = Carbon::now();
        $this->save();
    }

    /**
     * Limit query results to components that have not been recently refreshed
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStale(Builder $query): Builder
    {
        $interval = Config::get('happystack.status_refresh_interval');
        $threshold = Carbon::now()->subSeconds($interval);

        return $query->where('updated_at', '<=', $threshold);
    }

    /**
     * Return a boolean indicating whether the component is "healthy"
     *
     * @return bool
     */
    public function getIsHealthyAttribute(): bool
    {
        return $this->status === Status::OKAY;
    }
}
