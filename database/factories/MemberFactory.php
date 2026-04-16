<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->dateTimeBetween('-30 years', '-5 years')->format('Y-m-d'),
            'sport_id' => fake()->boolean(70) ? fake()->numberBetween(1, 3) : null,
            'level' => fake()->randomElement(['White Belt', 'Yellow Belt', 'Orange Belt', 'Green Belt', 'Blue Belt', 'Brown Belt', 'Black Belt', 'Beginner', 'Intermediate', 'Advanced']),
        ];
    }
}
