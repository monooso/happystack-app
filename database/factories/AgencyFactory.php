<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Agency;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

final class AgencyFactory extends Factory
{
    protected $model = Agency::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'via_mail'   => $this->faker->boolean(),
            'mail_route' => $this->faker->email(),
        ];
    }
}
