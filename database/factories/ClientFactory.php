<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'via_mail' => $this->faker->boolean(),
            'mail_route' => $this->faker->email(),
            'mail_message' => $this->faker->realText(),
            'notified_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
