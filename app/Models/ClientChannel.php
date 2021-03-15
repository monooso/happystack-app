<?php

declare(strict_types=1);

namespace App\Models;

use App\Constants\NotificationChannel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

final class ClientChannel extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'route', 'type'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Limit query results to those channels that can receive a notification
     *
     * We send a maximum of one client notification per 24 hours.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeCanReceiveNotification(Builder $query): Builder
    {
        return $query
            ->where(
                fn (Builder $query) => $query
                ->whereNull('last_notified_at')
                ->orWhere('last_notified_at', '<=', Carbon::now()->subDay())
            );
    }

    /**
     * Limit query results to 'mail' channels
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeToMail(Builder $query): Builder
    {
        return $query->where('type', NotificationChannel::MAIL);
    }
}
