<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Workout;

/**
 * @OA\Tag(
 *     name="Workout Plan",
 *     description="Manage user workout plans"
 * )
 */

class PlanController extends Controller
{   

    /**
     * @OA\Post(
     *     path="/api/plan/add",
     *     summary="Add a workout to user's plan",
     *     tags={"Workout Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"workout_id", "repetitions", "sets"},
     *             @OA\Property(property="workout_id", type="integer", example=1),
     *             @OA\Property(property="repetitions", type="integer", example=10),
     *             @OA\Property(property="sets", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Workout added to your plan"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Put(
     *     path="/api/plan/update",
     *     summary="Update repetitions and sets in the user's plan",
     *     tags={"Workout Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"workout_id", "repetitions", "sets"},
     *             @OA\Property(property="workout_id", type="integer", example=1),
     *             @OA\Property(property="repetitions", type="integer", example=12),
     *             @OA\Property(property="sets", type="integer", example=4)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Workout plan updated successfully"),
     *     @OA\Response(response=404, description="Workout not found in plan"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/api/plan",
     *     summary="Get user's workout plan",
     *     tags={"Workout Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of workouts in user's plan")
     * )
     */
        public function getPlan(Request $request)
        {
            $plan = $request->user()->workoutPlan()->get()->map(function ($workout) {
                return [
                    'id' => $workout->id,
                    'workout_id' => $workout->id, 
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
     * @OA\Delete(
     *     path="/api/plan/remove/{workout_id}",
     *     summary="Remove workout from user's plan",
     *     tags={"Workout Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="workout_id",
     *         in="path",
     *         required=true,
     *         description="Workout ID to remove",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Workout removed from plan"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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

        $exists = $request->user()->workoutPlan()->where('workout_id', $workout_id)->exists();

        if (!$exists) {
            return response()->json([
                'status' => false,
                'message' => 'Workout not found in your plan',
            ], 404);
        }

        $request->user()->workoutPlan()->detach($workout_id);

        return response()->json([
            'status' => true,
            'message' => 'Workout removed from your plan',
        ]);
    }
}
