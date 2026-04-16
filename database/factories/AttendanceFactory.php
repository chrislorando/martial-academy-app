<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_id' => fake()->numberBetween(1, 10),
            'member_id' => fake()->numberBetween(1, 20),
            'subscription_id' => fake()->numberBetween(1, 25),
            'date' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['Present', 'Present', 'Present', 'Late', 'Absent']),
            'marked_by' => fake()->numberBetween(1, 5),
        ];
    }
}
