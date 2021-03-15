<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ClientChannel;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ClientChannelFactory extends Factory
{
    protected $model = ClientChannel::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'type'       => 'mail',
            'route'      => $this->faker->email,
            'message'    => $this->faker->realText(),
        ];
    }
}
