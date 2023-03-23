<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @type array
     */
    protected $casts = ['personal_team' => 'boolean'];

    /**
     * The attributes that are mass assignable.
     *
     * @type array
     */
    protected $fillable = ['name', 'personal_team'];

    /**
     * The event map for the model.
     *
     * @type array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the projects which belong to this team
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
