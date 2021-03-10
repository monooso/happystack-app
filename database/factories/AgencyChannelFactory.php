<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AgencyChannel;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

final class AgencyChannelFactory extends Factory
{
    protected $model = AgencyChannel::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'type'       => 'mail',
            'route'      => $this->faker->email,
        ];
    }
}
