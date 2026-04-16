<?php

namespace Database\Seeders;

use App\Models\SportClass;
use App\Models\SportClassEnrollment;
use App\Models\User;
use App\Models\Member;
use App\Models\Sport;
use App\Models\Subscription;
use App\Models\ClassModel;
use App\Models\ClassEnrollment;
use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->createUsers();
        $this->createSports();
        $this->createMembers();
        $this->createSubscriptions();
        $this->createClasses();
        $this->createEnrollments();
        // $this->createAttendances();
    }

    private function createUsers(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'Admin',
        ]);

        User::factory()->create([
            'name' => 'Coach John',
            'email' => 'coach@example.com',
            'role' => 'Coach',
        ]);

        User::factory()->create([
            'name' => 'Coach Sarah',
            'email' => 'coach2@example.com',
            'role' => 'Coach',
        ]);

        User::factory()->create([
            'name' => 'Receptionist Jane',
            'email' => 'reception@example.com',
            'role' => 'Reception',
        ]);
    }

    private function createSports(): void
    {
        Sport::factory()->create(['name' => 'Karate']);
        Sport::factory()->create(['name' => 'Boxing']);
        Sport::factory()->create(['name' => 'Gymnastics']);
    }

    private function createMembers(): void
    {
        Member::factory(20)->create();
    }

    private function createSubscriptions(): void
    {
        $activeCount = 0;
        Member::all()->each(function ($member) use (&$activeCount) {
            if ($activeCount < 15 || fake()->boolean(30)) {
                Subscription::factory()->create([
                    'member_id' => $member->id,
                    'status' => 'Active',
                ]);
                $activeCount++;
            } else {
                Subscription::factory()->create([
                    'member_id' => $member->id,
                ]);
            }
        });
    }

    private function createClasses(): void
    {
        SportClass::factory(8)->create();
    }

    private function createEnrollments(): void
    {
        SportClass::all()->each(function ($class) {
            $memberCount = fake()->numberBetween(3, 12);
            $members = Member::inRandomOrder()->take($memberCount)->get();

            $members->each(function ($member) use ($class) {
                if (!$class->members()->where('members.id', $member->id)->exists()) {
                    SportClassEnrollment::factory()->create([
                        'sport_class_id' => $class->id,
                        'member_id' => $member->id,
                    ]);
                }
            });
        });
    }

    private function createAttendances(): void
    {
        SportClass::all()->each(function ($class) {
            $class->members->take(5)->each(function ($member) use ($class) {
                $attendanceCount = fake()->numberBetween(2, 4);

                for ($i = 0; $i < $attendanceCount; $i++) {
                    $date = fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d');

                    $subscription = $member->subscriptions()->inRandomOrder()->first();

                    Attendance::firstOrCreate(
                        [
                            'sport_class_id' => $class->id,
                            'member_id' => $member->id,
                            'date' => $date,
                        ],
                        [
                            'subscription_id' => $subscription ? $subscription->id : null,
                            'status' => fake()->randomElement(['Present', 'Present', 'Present', 'Late', 'Absent']),
                            'marked_by' => fake()->numberBetween(1, 4),
                        ]
                    );
                }
            });
        });
    }
}
