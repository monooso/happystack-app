<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function belongsToATeamReturnsTrueIfTheUserBelongsToAtLeastOneTeam()
    {
        $team = Team::factory()->create();

        $this->assertTrue($team->owner->belongsToATeam());
    }

    /** @test */
    public function belongsToATeamReturnsFalseIfTheUserDoesNotBelongToAnyTeams()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->belongsToATeam());
    }
}
