<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class StatusUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    /**
     * Get the component associated with this status
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
