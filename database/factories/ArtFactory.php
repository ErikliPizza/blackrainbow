<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Art;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Art>
 */
class ArtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->paragraph(3),
            'hours_spent' => fake()->numberBetween(1, 200),
            'views' => fake()->numberBetween(0, 100000),
        ];
    }
}
