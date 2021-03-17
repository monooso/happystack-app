<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'name'    => $this->faker->company,
        ];
    }
}
