<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'result' => $this->faker->numberBetween(0, 100),
            'grade' => strtoupper($this->faker->randomKey(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5]))
        ];
    }
}
