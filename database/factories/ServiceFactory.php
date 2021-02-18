<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'handle' => $this->faker->unique()->slug,
            'name'   => $this->faker->unique()->words(3, true),
        ];
    }
}