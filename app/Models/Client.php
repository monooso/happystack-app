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
    use Notifiable {
        notify as traitNotify;
        notifyNow as traitNotifyNow;
    }

    protected $dates = ['notified_at'];

    protected $fillable = [
        'mail_message',
        'mail_route',
        'notified_at',
        'via_mail',
    ];

    /**
     * Get the parent project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Can we notify this client of a component status change?
     *
     * We notify clients once every 24 hours, at most.
     */
    public function canBeNotified(): bool
    {
        if ($this->notified_at === null) {
            return true;
        }

        return Carbon::now()->subDay()->isAfter($this->notified_at);
    }

    /**
     * Get the notification email
     */
    public function routeNotificationForMail(): string
    {
        return $this->mail_route ?? '';
    }

    /**
     * Update the "notified_at" timestamp when notifying a client
     */
    public function notify(mixed $instance): void
    {
        $this->traitNotify($instance);

        $this->notified_at = Carbon::now();
        $this->save();
    }

    /**
     * Update the "notified_at" timestamp when notifying a client
     */
    public function notifyNow(mixed $instance, ?array $channels = null): void
    {
        $this->traitNotifyNow($instance, $channels);

        $this->notified_at = Carbon::now();
        $this->save();
    }
}
