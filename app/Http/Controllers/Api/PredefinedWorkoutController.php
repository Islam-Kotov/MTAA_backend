<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PredefinedWorkout;
use Illuminate\Http\Request;

class PredefinedWorkoutController extends Controller
{
    /**
     * Get list of workouts by level (Beginner / Advanced)
     */
    public function index(Request $request)
    {
        $level = strtolower($request->query('level')); // Приводим к нижнему регистру
    
        if (!in_array($level, ['beginner', 'advanced'])) {
            return response()->json(['message' => 'Invalid level'], 422);
        }
    
        $workouts = PredefinedWorkout::where('level', $level)
            ->select('id', 'title', 'image', 'duration', 'calories', 'exercise_count')
            ->get()
            ->map(function ($workout) {
                $workout->image = $workout->image 
                    ? asset('images/predefined/' . $workout->image) 
                    : null;
                return $workout;
            });
    
        return response()->json($workouts);
    }

    /**
     * Get full workout info by ID (including exercises)
     */
    public function show($id)
    {
        $workout = PredefinedWorkout::with('exercises')->find($id);

        if (!$workout) {
            return response()->json(['message' => 'Workout not found'], 404);
        }

        return response()->json([
            'title' => $workout->title,
            'focus' => $workout->focus,
            'duration' => $workout->duration,
            'calories' => $workout->calories,
            'sets_reps' => $workout->sets_reps,
            'rest' => $workout->rest,
            'benefits' => $workout->benefits,
            'image' => $workout->image ? asset('images/predefined/' . $workout->image) : null,
            'exercises' => $workout->exercises->map(function ($exercise) {
                return [
                    'name' => $exercise->title,
                    'reps_sets' => $exercise->reps_sets,
                    'description' => $exercise->guide,
                    'image' => $exercise->image 
                        ? asset('images/predefined/exercises/' . $exercise->image) 
                        : null,
                ];
            })
        ]);
    }
}
