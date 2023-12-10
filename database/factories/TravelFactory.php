<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_public' => $this->faker->boolean,
            'name' => $this->faker->text(30),
            'description' => $this->faker->text(100),
            'number_of_days' => rand(1, 10)
        ];
    }
}
