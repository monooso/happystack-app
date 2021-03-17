<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

final class Client extends Model
{
    use HasFactory;
    use Notifiable;

    protected $casts = ['last_updated_at' => 'datetime'];

    protected $fillable = [
        'last_updated_at',
        'mail_message',
        'mail_route',
        'via_mail',
    ];

    /**
     * Get the parent project
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Can we notify this client of a component status change?
     *
     * We notify clients once every 24 hours, at most.
     *
     * @return bool
     */
    public function getCanBeNotifiedAttribute(): bool
    {
        if ($this->last_notified_at === null) {
            return true;
        }

        return Carbon::now()->subDay()->isAfter($this->last_notified_at);
    }

    /**
     * Get the notification email
     *
     * @return string
     */
    public function routeNotificationForMail(): string
    {
        return $this->mail_route ?? '';
    }
}
