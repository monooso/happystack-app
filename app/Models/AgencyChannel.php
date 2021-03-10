<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AgencyChannel extends Model
{
    use HasFactory;

    protected $fillable = ['route', 'type'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
