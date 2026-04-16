<?php

namespace Database\Factories;

use App\Models\SportClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SportClass>
 */
class SportClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('08:00', '18:00');
        $endTime = (clone $startTime)->modify('+1 hour');
        
        return [
            'name' => fake()->randomElement(['Kids', 'Teens', 'Adults', 'Beginner', 'Intermediate', 'Advanced']) . ' ' . fake()->randomElement(['Karate', 'Boxing', 'Gymnastics']),
            'sport_id' => fake()->numberBetween(1, 3),
            'coach_id' => fake()->boolean(80) ? fake()->numberBetween(2, 4) : null,
            'day_of_week' => fake()->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'max_capacity' => fake()->randomElement([10, 15, 20, 25]),
        ];
    }
}
