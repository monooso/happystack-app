<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TeamInvitationFactory extends Factory
{
    protected $model = TeamInvitation::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'email' => $this->faker->email(),
            'role' => $this->faker->randomElement(['admin', 'member']),
        ];
    }
}
