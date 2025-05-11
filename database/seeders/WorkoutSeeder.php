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
            'execution_guide' => 'Stand with feet shoulder-width apart. Grip the bar with palms facing up. Curl the bar towards your chest while keeping elbows close to your torso. Slowly lower the bar back to the starting position.',
            'exercise_photo' => 'avatars/barbell-curl.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for chest',
            'exercise_name' => 'Bench Press',
            'main_muscles' => 'Chest',
            'equipment_req' => 'Barbell, Flat Bench',
            'execution_guide' => 'Lie flat on a bench. Grip the bar slightly wider than shoulder-width. Lower the bar to your chest slowly, then press it back up to full extension.',
            'exercise_photo' => 'avatars/bench-press.png',
        ]);

        Workout::create([
            'exercise_category' => 'Cardio Training',
            'exercise_type' => 'full body',
            'exercise_name' => 'Jumping Jacks',
            'main_muscles' => 'Full Body',
            'equipment_req' => 'None',
            'execution_guide' => 'Start with feet together and arms at sides. Jump while spreading your legs and raising arms overhead. Return to starting position and repeat.',
            'exercise_photo' => 'avatars/jumping-jacks.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for shoulders',
            'exercise_name' => 'Overhead Press',
            'main_muscles' => 'Shoulders',
            'equipment_req' => 'Barbell',
            'execution_guide' => 'Stand with the barbell at shoulder height. Press it overhead until arms are fully extended. Lower the bar under control.',
            'exercise_photo' => 'avatars/overhead-press.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for back',
            'exercise_name' => 'Deadlift',
            'main_muscles' => 'Back, Hamstrings',
            'equipment_req' => 'Barbell',
            'execution_guide' => 'Stand with mid-foot under the bar. Bend at hips and knees, grip the bar. Lift by straightening your back and legs. Keep back flat throughout the movement.',
            'exercise_photo' => 'avatars/deadlift.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for legs',
            'exercise_name' => 'Barbell Squat',
            'main_muscles' => 'Quadriceps, Glutes',
            'equipment_req' => 'Barbell, Squat Rack',
            'execution_guide' => 'Place barbell on upper back. Squat down until thighs are parallel to the ground. Keep knees aligned with toes. Push through heels to return to standing.',
            'exercise_photo' => 'avatars/squat.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for triceps',
            'exercise_name' => 'Triceps Dips',
            'main_muscles' => 'Triceps',
            'equipment_req' => 'Dip Bars or Bench',
            'execution_guide' => 'Place hands behind on bench or bars. Lower your body by bending elbows until arms are at 90°. Push back up to start.',
            'exercise_photo' => 'avatars/triceps-dips.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for back',
            'exercise_name' => 'Pull-ups',
            'main_muscles' => 'Lats, Biceps',
            'equipment_req' => 'Pull-up Bar',
            'execution_guide' => 'Hang from bar with overhand grip. Pull your body up until chin is above the bar. Lower slowly.',
            'exercise_photo' => 'avatars/pull-ups.png',
        ]);

        Workout::create([
            'exercise_category' => 'Cardio Training',
            'exercise_type' => 'full body',
            'exercise_name' => 'Burpees',
            'main_muscles' => 'Full Body',
            'equipment_req' => 'None',
            'execution_guide' => 'Start standing. Drop to squat, kick feet back to plank, do a push-up, jump back in and leap upward.',
            'exercise_photo' => 'avatars/burpees.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for legs',
            'exercise_name' => 'Lunges',
            'main_muscles' => 'Quadriceps, Glutes',
            'equipment_req' => 'Dumbbells (optional)',
            'execution_guide' => 'Step forward with one leg and lower your body until both knees are at 90°. Push back up and repeat with the other leg.',
            'exercise_photo' => 'avatars/lunges.png',
        ]);

        Workout::create([
            'exercise_category' => 'Core Training',
            'exercise_type' => 'for abs',
            'exercise_name' => 'Plank',
            'main_muscles' => 'Core',
            'equipment_req' => 'Mat',
            'execution_guide' => 'Lie face down, then lift body on elbows and toes. Keep body straight. Hold for time.',
            'exercise_photo' => 'avatars/plank.png',
        ]);

        Workout::create([
            'exercise_category' => 'Core Training',
            'exercise_type' => 'for abs',
            'exercise_name' => 'Crunches',
            'main_muscles' => 'Abdominals',
            'equipment_req' => 'Mat',
            'execution_guide' => 'Lie on your back with knees bent. Lift shoulders off the floor using your core, then lower back down.',
            'exercise_photo' => 'avatars/crunches.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for shoulders',
            'exercise_name' => 'Lateral Raise',
            'main_muscles' => 'Shoulders',
            'equipment_req' => 'Dumbbells',
            'execution_guide' => 'Hold dumbbells at sides. Raise arms to shoulder height with a slight bend in elbows. Lower slowly.',
            'exercise_photo' => 'avatars/lateral-raise.png',
        ]);

        Workout::create([
            'exercise_category' => 'Cardio Training',
            'exercise_type' => 'for legs',
            'exercise_name' => 'High Knees',
            'main_muscles' => 'Legs, Core',
            'equipment_req' => 'None',
            'execution_guide' => 'Run in place bringing your knees up to waist level quickly. Pump your arms for balance.',
            'exercise_photo' => 'avatars/high-knees.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for chest',
            'exercise_name' => 'Incline Dumbbell Press',
            'main_muscles' => 'Upper Chest',
            'equipment_req' => 'Incline Bench, Dumbbells',
            'execution_guide' => 'Lie on incline bench. Press dumbbells upward, keeping elbows at 90°. Lower slowly back to chest level.',
            'exercise_photo' => 'avatars/incline-dumbbell-press.png',
        ]);

        Workout::create([
            'exercise_category' => 'Core Training',
            'exercise_type' => 'for abs',
            'exercise_name' => 'Leg Raises',
            'main_muscles' => 'Lower Abs',
            'equipment_req' => 'None',
            'execution_guide' => 'Lie on your back. Keep legs straight and raise them to 90°. Lower slowly without touching the floor.',
            'exercise_photo' => 'avatars/leg-raises.png',
        ]);

        Workout::create([
            'exercise_category' => 'Cardio Training',
            'exercise_type' => 'full body',
            'exercise_name' => 'Mountain Climbers',
            'main_muscles' => 'Core, Legs',
            'equipment_req' => 'None',
            'execution_guide' => 'Get into plank. Drive one knee towards chest, switch quickly to the other. Continue alternating.',
            'exercise_photo' => 'avatars/mountain-climbers.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for biceps',
            'exercise_name' => 'Hammer Curls',
            'main_muscles' => 'Biceps, Forearms',
            'equipment_req' => 'Dumbbells',
            'execution_guide' => 'Hold dumbbells with thumbs facing up. Curl them simultaneously while keeping elbows tucked. Lower slowly.',
            'exercise_photo' => 'avatars/hammer-curls.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for triceps',
            'exercise_name' => 'Skull Crushers',
            'main_muscles' => 'Triceps',
            'equipment_req' => 'EZ Bar',
            'execution_guide' => 'Lie on bench, hold bar with arms extended. Lower the bar towards your forehead by bending elbows, then extend back up.',
            'exercise_photo' => 'avatars/skull-crushers.png',
        ]);

        Workout::create([
            'exercise_category' => 'Weight Training',
            'exercise_type' => 'for shoulders',
            'exercise_name' => 'Front Raise',
            'main_muscles' => 'Anterior Deltoid',
            'equipment_req' => 'Dumbbells',
            'execution_guide' => 'Hold dumbbells in front of thighs. Raise arms to shoulder height, then lower back down slowly.',
            'exercise_photo' => 'avatars/front-raise.png',
        ]);

        // HOME WORKOUTS — добавленные 5
        Workout::create([
            'exercise_category' => 'Home Workouts',
            'exercise_type' => 'for legs',
            'exercise_name' => 'Wall Sit',
            'main_muscles' => 'Quadriceps',
            'equipment_req' => 'Wall',
            'execution_guide' => 'Stand with your back against a wall. Slide down until your knees are at a 90-degree angle. Hold the position while keeping your back flat against the wall.',
            'exercise_photo' => 'avatars/wall-sit.png',
        ]);

        Workout::create([
            'exercise_category' => 'Home Workouts',
            'exercise_type' => 'for chest',
            'exercise_name' => 'Push-ups',
            'main_muscles' => 'Chest, Triceps, Shoulders',
            'equipment_req' => 'None',
            'execution_guide' => 'Start in plank position. Lower your body by bending elbows until your chest nearly touches the floor. Push back up to starting position.',
            'exercise_photo' => 'avatars/push-ups.png',
        ]);

        Workout::create([
            'exercise_category' => 'Home Workouts',
            'exercise_type' => 'for core',
            'exercise_name' => 'Bicycle Crunches',
            'main_muscles' => 'Abdominals, Obliques',
            'equipment_req' => 'None',
            'execution_guide' => 'Lie on your back with hands behind your head. Bring knees towards chest and alternate elbow to opposite knee while extending the other leg.',
            'exercise_photo' => 'avatars/bicycle-crunches.png',
        ]);

        Workout::create([
            'exercise_category' => 'Home Workouts',
            'exercise_type' => 'for full body',
            'exercise_name' => 'Shadow Boxing',
            'main_muscles' => 'Arms, Shoulders, Core',
            'equipment_req' => 'None',
            'execution_guide' => 'Stand in fighting stance. Throw quick punches into the air, alternating arms and adding foot movement to engage the whole body.',
            'exercise_photo' => 'avatars/shadow-boxing.png',
        ]);

        Workout::create([
            'exercise_category' => 'Home Workouts',
            'exercise_type' => 'for glutes',
            'exercise_name' => 'Glute Bridges',
            'main_muscles' => 'Glutes, Hamstrings',
            'equipment_req' => 'Mat',
            'execution_guide' => 'Lie on your back with knees bent. Lift your hips up by squeezing your glutes until your body forms a straight line from shoulders to knees. Lower and repeat.',
            'exercise_photo' => 'avatars/glute-bridges.png',
        ]);
    }
}
