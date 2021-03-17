<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Agency extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['mail_route', 'via_mail'];

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
     * Get the notification email
     *
     * @return string
     */
    public function routeNotificationForMail(): string
    {
        return $this->mail_route ?? '';
    }
}
