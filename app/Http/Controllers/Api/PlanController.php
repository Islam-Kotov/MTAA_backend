<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Workout;

class PlanController extends Controller
{   
    /**
     * add exercise into user's plan
     */
    public function addToPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workout_id' => 'required|exists:workouts,id',
            'repetitions' => 'required|integer|min:1',
            'sets' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $request->user()->workoutPlan()->syncWithoutDetaching([
            $request->workout_id => [
                'repetitions' => $request->repetitions,
                'sets' => $request->sets,
            ]
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Workout added to your plan',
        ], 200);
    }



    /**
     * update repetitions and sets for an exercise in user's plan
     */
    public function updatePlanItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workout_id' => 'required|exists:workouts,id',
            'repetitions' => 'required|integer|min:1',
            'sets' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = $request->user()
            ->workoutPlan()
            ->where('workout_id', $request->workout_id)
            ->exists();

        if (!$exists) {
            return response()->json([
                'status' => false,
                'message' => 'Workout not found in your plan',
            ], 404);
        }

        $request->user()->workoutPlan()->updateExistingPivot(
            $request->workout_id,
            [
                'repetitions' => $request->repetitions,
                'sets' => $request->sets,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Workout plan updated successfully',
        ]);
    }



    /**
     * get user's plan
     */
    public function getPlan(Request $request)
    {
        $plan = $request->user()->workoutPlan()->get()->map(function ($workout) {
            return [
                'id' => $workout->id,
                'exercise_name' => $workout->exercise_name,
                'main_muscles' => $workout->main_muscles,
                'equipment_req' => $workout->equipment_req,
                'execution_guide' => $workout->execution_guide,
                'repetitions' => $workout->pivot->repetitions,
                'sets' => $workout->pivot->sets,
                'photo_url' => $workout->photo_url,
            ];
        });

        return response()->json($plan);
    }

    /**
     * delete from the plan
     */
    public function removeFromPlan(Request $request, $workout_id)
    {
        $validator = Validator::make(['workout_id' => $workout_id], [
            'workout_id' => 'required|exists:workouts,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $request->user()->workoutPlan()->detach($workout_id);
    
        return response()->json([
            'status' => true,
            'message' => 'Workout removed from your plan',
        ]);
    }
}
