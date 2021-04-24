<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Constants\Status;
use App\Models\Component;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ComponentFactory extends Factory
{
    protected $model = Component::class;

    public function definition()
    {
        return [
            'service_id'        => Service::factory(),
            'handle'            => $this->faker->unique()->slug(),
            'name'              => $this->faker->unique()->words(3, true),
            'status'            => $this->faker->randomElement(Status::all()),
            'status_updated_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
