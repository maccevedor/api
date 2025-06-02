<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'monthly_price' => $this->faker->randomFloat(2, 10, 100),
            'user_limit' => $this->faker->numberBetween(1, 50),
        ];
    }
}
