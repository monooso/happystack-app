<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ComponentFactory extends Factory
{
    protected $model = Component::class;

    public function definition()
    {
        return [
            'handle' => $this->faker->unique()->slug,
            'name'   => $this->faker->unique()->words(3, true),
        ];
    }
}
