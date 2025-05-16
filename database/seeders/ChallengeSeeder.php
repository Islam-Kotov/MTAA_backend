<?php

namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Challenge::create([
            'description' => 'Do 30 pushups.',
        ]);

        Challenge::create([
            'description' => 'Do 30 squats.',
        ]);

        Challenge::create([
            'description' => 'Go for a run.',
        ]);
        
        Challenge::create([
            'description' => 'Do 30 pullups.',
        ]);
    }
}
