<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    /**
     * categories
     */
    public function categories()
    {
        $categories = Workout::select('exercise_category')
            ->distinct()
            ->pluck('exercise_category');

        return response()->json($categories);
    }

    /**
     * list of exercises according to category
     */
    public function index(Request $request)
    {
        $query = Workout::query();

        if ($request->has('category')) {
            $query->where('exercise_category', $request->category);
        }

        $workouts = $query->get([
            'id',
            'exercise_name',
            'exercise_type',
            'exercise_photo',
        ]);

        $workouts->transform(function ($item) {
            $item->exercise_photo = $item->exercise_photo
                ? asset($item->exercise_photo)
                : null;
            return $item;
        });

        return response()->json($workouts);
    }

    /**
     * exact exercise
     */
    public function show($id)
    {
        $workout = Workout::find($id);

        if (!$workout) {
            return response()->json(['message' => 'Exercise not found'], 404);
        }

        return response()->json([
            'exercise_name' => $workout->exercise_name,
            'main_muscles' => $workout->main_muscles,
            'equipment_req' => $workout->equipment_req,
            'execution_guide' => $workout->execution_guide,
            'exercise_photo' => $workout->exercise_photo
                ? asset($workout->exercise_photo)
                : null,
        ]);
    }
}
