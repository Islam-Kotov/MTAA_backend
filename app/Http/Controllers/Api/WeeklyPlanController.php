<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WeeklyPlan;
use App\Models\WeeklyPlanItem;
use App\Models\Workout;

/**
 * @OA\Tag(
 *     name="Weekly Plan",
 *     description="Manage user's weekly workout schedule"
 * )
 */
class WeeklyPlanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/weekly-plan",
     *     summary="Get workouts grouped by day of week",
     *     tags={"Weekly Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of workouts grouped by days",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="day", type="string", example="Monday"),
     *                 @OA\Property(
     *                     property="workouts",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="workout_id", type="integer", example=1),
     *                         @OA\Property(property="exercise_name", type="string", example="Push-up"),
     *                         @OA\Property(property="sets", type="integer", example=3),
     *                         @OA\Property(property="repetitions", type="integer", example=10)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $plans = $request->user()->weeklyPlans()->with('items.workout')->get();

        $result = $plans->map(function ($plan) {
            return [
                'day' => $plan->day_of_week,
                'workouts' => $plan->items->map(function ($item) {
                    return [
                        'workout_id' => $item->workout_id,
                        'exercise_name' => $item->workout->exercise_name,
                        'sets' => $item->sets,
                        'repetitions' => $item->repetitions,
                    ];
                }),
            ];
        });

        return response()->json($result);
    }

    /**
     * @OA\Post(
     *     path="/api/weekly-plan/add",
     *     summary="Add or update a workout to a specific day",
     *     tags={"Weekly Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"day_of_week", "workout_id", "sets", "repetitions"},
     *             @OA\Property(property="day_of_week", type="string", example="Monday"),
     *             @OA\Property(property="workout_id", type="integer", example=1),
     *             @OA\Property(property="sets", type="integer", example=3),
     *             @OA\Property(property="repetitions", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Workout added to weekly plan"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function addWorkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day_of_week' => 'required|string',
            'workout_id' => 'required|exists:workouts,id',
            'sets' => 'required|integer|min:1',
            'repetitions' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $weeklyPlan = WeeklyPlan::firstOrCreate([
            'user_id' => $request->user()->id,
            'day_of_week' => $request->day_of_week,
        ]);

        WeeklyPlanItem::updateOrCreate(
            [
                'weekly_plan_id' => $weeklyPlan->id,
                'workout_id' => $request->workout_id,
            ],
            [
                'sets' => $request->sets,
                'repetitions' => $request->repetitions,
            ]
        );

        return response()->json(['message' => 'Workout added to weekly plan']);
    }

    /**
     * @OA\Delete(
     *     path="/api/weekly-plan/remove",
     *     summary="Remove a workout from a specific day",
     *     tags={"Weekly Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="day_of_week",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="Tuesday"
     *     ),
     *     @OA\Parameter(
     *         name="workout_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         example=2
     *     ),
     *     @OA\Response(response=200, description="Workout removed from weekly plan"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Plan not found")
     * )
     */

    public function removeWorkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day_of_week' => 'required|string',
            'workout_id' => 'required|exists:workouts,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $plan = WeeklyPlan::where('user_id', $request->user()->id)
            ->where('day_of_week', $request->day_of_week)
            ->first();

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        WeeklyPlanItem::where('weekly_plan_id', $plan->id)
            ->where('workout_id', $request->workout_id)
            ->delete();

        
        $remainingItems = WeeklyPlanItem::where('weekly_plan_id', $plan->id)->count();

        if ($remainingItems === 0) {
            $plan->delete();
        }

        return response()->json(['message' => 'Workout removed from weekly plan']);
    }

}
