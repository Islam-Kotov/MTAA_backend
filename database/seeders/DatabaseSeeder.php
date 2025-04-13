<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Teseeet User',
            'email' => 'teeeeeest@example.com',
        ]);


        $this->call([
            WorkoutSeeder::class,
            PredefinedWorkoutSeeder::class,
        ]);
    }
}
