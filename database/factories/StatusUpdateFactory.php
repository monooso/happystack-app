<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Constants\Status;
use App\Models\StatusUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

final class StatusUpdateFactory extends Factory
{
    protected $model = StatusUpdate::class;

    public function definition()
    {
        return ['status' => $this->faker->randomElement(Status::all())];
    }
}
