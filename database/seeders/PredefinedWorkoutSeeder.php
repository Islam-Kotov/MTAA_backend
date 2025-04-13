<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PredefinedWorkout;
use App\Models\PredefinedWorkoutExercise;

class PredefinedWorkoutSeeder extends Seeder
{
    public function run(): void
    {
        // Beginner - Upper Body Workout
        $beginnerWorkout = PredefinedWorkout::create([
            'level' => 'beginner',
            'title' => 'Upper Body Workout',
            'image' => 'upper_body.png',
            'duration' => '40 minutes',
            'calories' => '400-450 kcal',
            'exercise_count' => 3,
            'focus' => 'Arms, shoulders, chest, and back',
            'sets_reps' => '3-4 sets of 12-15 reps',
            'rest' => '30-40 seconds',
            'benefits' => "Strengthens arms, shoulders, and upper back\nImproves posture and stability\nIncreases endurance without heavy weights\nHelps beginners build strength for push-ups"
        ]);

        PredefinedWorkoutExercise::insert([
            [
                'predefined_workout_id' => $beginnerWorkout->id,
                'title' => 'Wall Push-Ups',
                'image' => 'wall_pushups.png',
                'reps_sets' => '3x15',
                'guide' => "Push against a wall while maintaining a straight body.\nGreat for beginners to build chest strength."
            ],
            [
                'predefined_workout_id' => $beginnerWorkout->id,
                'title' => 'Knee Push-Ups',
                'image' => 'knee_pushups.png',
                'reps_sets' => '3x12',
                'guide' => "Keep your knees on the ground for extra support.\nFocus on controlled movement."
            ],
            [
                'predefined_workout_id' => $beginnerWorkout->id,
                'title' => 'Dumbbell Shoulder Press',
                'image' => 'shoulder_press.png',
                'reps_sets' => '3x12',
                'guide' => "Hold dumbbells (or water bottles) at shoulder height.\nPress upward until arms are extended.\nLower back slowly."
            ],
        ]);

        // Advanced - Full Body Blast
        $advancedWorkout = PredefinedWorkout::create([
            'level' => 'advanced',
            'title' => 'Full Body Blast',
            'image' => 'full_body.png',
            'duration' => '50 minutes',
            'calories' => '500-600 kcal',
            'exercise_count' => 4,
            'focus' => 'Total body strength and endurance',
            'sets_reps' => '4 sets of 15 reps',
            'rest' => '20-30 seconds',
            'benefits' => "Maximizes calorie burn\nImproves cardiovascular endurance\nEngages full-body muscle groups\nGreat for strength & toning"
        ]);

        PredefinedWorkoutExercise::insert([
            [
                'predefined_workout_id' => $advancedWorkout->id,
                'title' => 'Burpees',
                'image' => 'burpees.png',
                'reps_sets' => '4x15',
                'guide' => "Explosive movement engaging legs, arms, and core.\nPerform with high intensity for max results."
            ],
            [
                'predefined_workout_id' => $advancedWorkout->id,
                'title' => 'Jump Squats',
                'image' => 'jump_squats.png',
                'reps_sets' => '4x15',
                'guide' => "Squat down and jump explosively.\nLand softly with bent knees.\nTargets glutes, quads, and calves."
            ],
            [
                'predefined_workout_id' => $advancedWorkout->id,
                'title' => 'Push-Ups',
                'image' => 'pushups.png',
                'reps_sets' => '4x15',
                'guide' => "Standard push-up with full range motion.\nKeep your core tight and back straight."
            ],
            [
                'predefined_workout_id' => $advancedWorkout->id,
                'title' => 'Mountain Climbers',
                'image' => 'mountain_climbers.png',
                'reps_sets' => '4x30s',
                'guide' => "Quickly drive knees to chest while in plank position.\nWorks core, shoulders, and cardio."
            ],
        ]);
    }
}
