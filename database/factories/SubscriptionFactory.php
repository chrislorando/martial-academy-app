<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['Monthly', 'Session Based']);
        $startDate = fake()->dateTimeBetween('-3 months', 'now');
        
        if ($type === 'Monthly') {
            $endDate = (clone $startDate)->modify('+30 days');
            $sessionsRemaining = null;
            $value = 30;
        } else {
            $endDate = (clone $startDate)->modify('+1 year');
            $sessionsRemaining = fake()->randomElement([10, 20]);
            $value = $sessionsRemaining;
        }
        
        $status = 'Active';
        if ($endDate < now()) {
            $status = 'Expired';
        } elseif ($sessionsRemaining === 0) {
            $status = 'Completed';
        }

        return [
            'member_id' => fake()->numberBetween(1, 20),
            'subscription_type' => $type,
            'subscription_value' => $value,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'sessions_remaining' => $sessionsRemaining,
            'status' => $status,
        ];
    }
}
