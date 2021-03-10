<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ClientChannel extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'route', 'type'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
