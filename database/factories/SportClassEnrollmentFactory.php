<?php

namespace Database\Factories;

use App\Models\SportClassEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SportClassEnrollment>
 */
class SportClassEnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sport_class_id' => fake()->numberBetween(1, 10),
            'member_id' => fake()->numberBetween(1, 20),
            'enrolled_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
