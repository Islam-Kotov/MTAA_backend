<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workout;

class WorkoutSeeder extends Seeder
{
    public function run(): void
    {
        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for biceps',
            'exercise_name' => 'Barbell Curl',
            'main_muscles' => 'Biceps',
            'equipment_req' => 'EZ Barbell',
            'execution_guide' => 'Stand with your feet shoulder-width apart...',
            'exercise_photo' => 'avatar/barbell-curl.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for chest',
            'exercise_name' => 'Bench Press',
            'main_muscles' => 'Chest',
            'equipment_req' => 'Barbell',
            'execution_guide' => 'Lie on the bench. Lower the bar slowly...',
            'exercise_photo' => 'avatar/bench-press.png',
        ]);

        Workout::create([
            'exercise_category' => 'Cardio Training',
            'exercise_type' => 'full body',
            'exercise_name' => 'Jumping Jacks',
            'main_muscles' => 'Full Body',
            'equipment_req' => 'None',
            'execution_guide' => 'Jump while spreading legs and raising arms...',
            'exercise_photo' => 'avatar/jumping-jacks.png',
        ]);
    }
}
