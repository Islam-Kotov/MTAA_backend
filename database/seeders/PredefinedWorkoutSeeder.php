<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PredefinedWorkout;
use App\Models\PredefinedWorkoutExercise;

class PredefinedWorkoutSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Beginner - Upper Body Workout
        $w1 = PredefinedWorkout::create([
            'level' => 'beginner',
            'title' => 'Upper Body Workout',
            'image' => 'avatars/upper_body.png',
            'duration' => '40 minutes',
            'calories' => '400-450 kcal',
            'exercise_count' => 3,
            'focus' => 'Arms, shoulders, chest, and back',
            'sets_reps' => '3-4 sets of 12-15 reps',
            'rest' => '30-40 seconds',
            'benefits' => "Strengthens arms, shoulders, and upper back\nImproves posture and stability\nIncreases endurance without heavy weights"
        ]);

        PredefinedWorkoutExercise::insert([
            [
                'predefined_workout_id' => $w1->id,
                'title' => 'Wall Push-Ups',
                'image' => 'avatars/wall_push-ups.png',
                'reps_sets' => '3x15',
                'guide' => "Push against a wall while maintaining a straight body.\nGreat for beginners to build chest strength."
            ],
            [
                'predefined_workout_id' => $w1->id,
                'title' => 'Knee Push-Ups',
                'image' => 'avatars/knee_push-ups.png',
                'reps_sets' => '3x12',
                'guide' => "Keep your knees on the ground for extra support.\nFocus on controlled movement."
            ],
            [
                'predefined_workout_id' => $w1->id,
                'title' => 'Dumbbell Shoulder Press',
                'image' => 'avatars/shoulder_press.png',
                'reps_sets' => '3x12',
                'guide' => "Hold dumbbells (or water bottles) at shoulder height.\nPress upward until arms are extended.\nLower back slowly."
            ],
        ]);

        // 2. Advanced - Full Body Blast
        $w2 = PredefinedWorkout::create([
            'level' => 'advanced',
            'title' => 'Full Body Blast',
            'image' => 'avatars/full_body.png',
            'duration' => '50 minutes',
            'calories' => '500-600 kcal',
            'exercise_count' => 4,
            'focus' => 'Total body strength and endurance',
            'sets_reps' => '4 sets of 15 reps',
            'rest' => '20-30 seconds',
            'benefits' => "Maximizes calorie burn\nImproves cardiovascular endurance\nEngages full-body muscle groups"
        ]);

        PredefinedWorkoutExercise::insert([
            [
                'predefined_workout_id' => $w2->id,
                'title' => 'Burpees',
                'image' => 'avatars/burpees.png',
                'reps_sets' => '4x15',
                'guide' => "Explosive movement engaging legs, arms, and core.\nPerform with high intensity for max results."
            ],
            [
                'predefined_workout_id' => $w2->id,
                'title' => 'Jump Squats',
                'image' => 'avatars/jump_squats.png',
                'reps_sets' => '4x15',
                'guide' => "Squat down and jump explosively.\nLand softly with bent knees.\nTargets glutes, quads, and calves."
            ],
            [
                'predefined_workout_id' => $w2->id,
                'title' => 'Push-Ups',
                'image' => 'avatars/push-ups.png',
                'reps_sets' => '4x15',
                'guide' => "Standard push-up with full range motion.\nKeep your core tight and back straight."
            ],
            [
                'predefined_workout_id' => $w2->id,
                'title' => 'Mountain Climbers',
                'image' => 'avatars/mountain_climbers.png',
                'reps_sets' => '4x30s',
                'guide' => "Quickly drive knees to chest while in plank position.\nWorks core, shoulders, and cardio."
            ],
        ]);

        // 3. Beginner - Core Stability
        $w3 = PredefinedWorkout::create([
            'level' => 'beginner',
            'title' => 'Core Stability',
            'image' => 'avatars/core_stability.png',
            'duration' => '35 minutes',
            'calories' => '300-350 kcal',
            'exercise_count' => 4,
            'focus' => 'Abs, lower back, obliques',
            'sets_reps' => '3 sets of 20s-30s',
            'rest' => '30 seconds',
            'benefits' => "Strengthens core\nImproves stability\nSupports spine health\nGood for all levels"
        ]);

        PredefinedWorkoutExercise::insert([
            [
                'predefined_workout_id' => $w3->id,
                'title' => 'Plank',
                'image' => 'avatars/plank.png',
                'reps_sets' => '3x30s',
                'guide' => "Hold plank position with straight body.\nFocus on engaging core and avoiding sagging hips."
            ],
            [
                'predefined_workout_id' => $w3->id,
                'title' => 'Crunches',
                'image' => 'avatars/crunches.png',
                'reps_sets' => '3x15',
                'guide' => "Lift upper back from floor.\nKeep lower back and feet on ground.\nEngage abs during lift."
            ],
            [
                'predefined_workout_id' => $w3->id,
                'title' => 'Leg Raises',
                'image' => 'avatars/leg-raises.png',
                'reps_sets' => '3x12',
                'guide' => "Lift legs from floor to 90° angle.\nLower slowly without touching ground."
            ],
            [
                'predefined_workout_id' => $w3->id,
                'title' => 'Bicycle Crunches',
                'image' => 'avatars/bicycle-crunches.png',
                'reps_sets' => '3x20',
                'guide' => "Alternate elbow to opposite knee while cycling legs.\nKeep core tight."
            ],
        ]);

        // 4. Advanced - Leg Power Circuit
        $w4 = PredefinedWorkout::create([
            'level' => 'advanced',
            'title' => 'Leg Power Circuit',
            'image' => 'avatars/leg_power.png',
            'duration' => '45 minutes',
            'calories' => '450-500 kcal',
            'exercise_count' => 5,
            'focus' => 'Legs, glutes, calves',
            'sets_reps' => '4 sets of 12-15 reps',
            'rest' => '40 seconds',
            'benefits' => "Builds explosive power\nTones lower body\nImproves balance and stability"
        ]);

        PredefinedWorkoutExercise::insert([
            [
                'predefined_workout_id' => $w4->id,
                'title' => 'Barbell Squats',
                'image' => 'avatars/squat.png',
                'reps_sets' => '4x12',
                'guide' => "Squat down keeping back straight.\nKnees should not pass toes.\nPush through heels."
            ],
            [
                'predefined_workout_id' => $w4->id,
                'title' => 'Lunges',
                'image' => 'avatars/lunges.png',
                'reps_sets' => '4x12 each leg',
                'guide' => "Step forward and lower until knees at 90°.\nReturn and switch legs."
            ],
            [
                'predefined_workout_id' => $w4->id,
                'title' => 'Jump Squats',
                'image' => 'avatars/jump_squats.png',
                'reps_sets' => '4x15',
                'guide' => "Explode upwards from a squat.\nLand with knees soft.\nEngages glutes and quads."
            ],
            [
                'predefined_workout_id' => $w4->id,
                'title' => 'Wall Sit',
                'image' => 'avatars/wall-sit.png',
                'reps_sets' => '4x30s',
                'guide' => "Hold 90° seated position against wall.\nKeep back flat and thighs parallel."
            ],
            [
                'predefined_workout_id' => $w4->id,
                'title' => 'Calf Raises',
                'image' => 'avatars/calf_raises.png',
                'reps_sets' => '4x20',
                'guide' => "Stand on toes then lower.\nHold onto wall for balance.\nFocus on full range of motion."
            ],
        ]);

        // 5. Mixed - Home HIIT
        $w5 = PredefinedWorkout::create([
            'level' => 'beginner',
            'title' => 'Home HIIT Challenge',
            'image' => 'avatars/home_hiit.png',
            'duration' => '30 minutes',
            'calories' => '350-400 kcal',
            'exercise_count' => 6,
            'focus' => 'Full body HIIT at home',
            'sets_reps' => '3 rounds of 30s on / 15s off',
            'rest' => '15 seconds between exercises',
            'benefits' => "No equipment needed\nBoosts metabolism\nFast-paced and fun\nPerfect for fat burn"
        ]);

        PredefinedWorkoutExercise::insert([
            [
                'predefined_workout_id' => $w5->id,
                'title' => 'High Knees',
                'image' => 'avatars/high-knees.png',
                'reps_sets' => '3x30s',
                'guide' => "Run in place lifting knees high.\nKeep arms moving and back straight."
            ],
            [
                'predefined_workout_id' => $w5->id,
                'title' => 'Shadow Boxing',
                'image' => 'avatars/shadow-boxing.png',
                'reps_sets' => '3x30s',
                'guide' => "Throw quick punches into the air.\nStay light on your feet and keep moving."
            ],
            [
                'predefined_workout_id' => $w5->id,
                'title' => 'Glute Bridges',
                'image' => 'avatars/glute-bridges.png',
                'reps_sets' => '3x20',
                'guide' => "Lift hips up from the floor.\nSqueeze glutes at the top.\nLower slowly."
            ],
            [
                'predefined_workout_id' => $w5->id,
                'title' => 'Push-Ups',
                'image' => 'avatars/push-ups.png',
                'reps_sets' => '3x15',
                'guide' => "Standard push-up.\nEngage core and maintain form throughout."
            ],
            [
                'predefined_workout_id' => $w5->id,
                'title' => 'Plank',
                'image' => 'avatars/plank.png',
                'reps_sets' => '3x30s',
                'guide' => "Keep body in straight line.\nTighten core and glutes.\nDon’t arch back."
            ],
            [
                'predefined_workout_id' => $w5->id,
                'title' => 'Burpees',
                'image' => 'avatars/burpees.png',
                'reps_sets' => '3x15',
                'guide' => "Full body explosive movement.\nMaintain intensity and form."
            ],
        ]);
    }
}
