<?php

namespace Database\Factories;

use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentFactory extends Factory
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
