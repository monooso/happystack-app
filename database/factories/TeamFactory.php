<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->unique()->company,
            'user_id'       => User::factory(),
            'personal_team' => true,
        ];
    }

    /**
     * Create a team with an active subscription to the specific plan
     *
     * @param int|null $planId
     *
     * @return TeamFactory
     */
    public function withActiveSubscription(int $planId = null)
    {
        return $this->afterCreating(function (Team $team) use ($planId) {
            optional($team->customer)->update(['trial_ends_at' => null]);

            $team->subscriptions()->create([
                'name'          => 'default',
                'paddle_id'     => random_int(1, 1000),
                'paddle_status' => 'active',
                'paddle_plan'   => $planId,
                'quantity'      => 1,
                'trial_ends_at' => null,
                'paused_from'   => null,
                'ends_at'       => null,
            ]);
        });
    }

    /**
     * Create a team with an expired trial
     *
     * @return TeamFactory
     */
    public function withExpiredTrial()
    {
        return $this->afterCreating(function (Team $team) {
            $team->customer->update(['trial_ends_at' => $team->created_at]);
        });
    }
}
