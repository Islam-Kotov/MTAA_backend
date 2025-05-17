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
     *                 @OA\Property(property="title", type="string", example="Leg Day"),
     *                 @OA\Property(property="description", type="string", example="Focus on quads and glutes"),
     *                 @OA\Property(property="scheduled_time", type="string", example="14:30:00"),
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
                'title' => $plan->title,
                'description' => $plan->description,
                'scheduled_time' => $plan->scheduled_time, // строка с "HH:mm:ss"
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
     *             @OA\Property(property="title", type="string", example="Leg Day"),
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
            'title' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $weeklyPlan = WeeklyPlan::firstOrCreate([
            'user_id' => $request->user()->id,
            'day_of_week' => $request->day_of_week,
        ]);

        if ($request->filled('title') && $weeklyPlan->title !== $request->title) {
            $weeklyPlan->title = $request->title;
            $weeklyPlan->save();
        }

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
     * @OA\Patch(
     *     path="/api/weekly-plan/update-meta",
     *     summary="Update title, description and scheduled_time for a specific day",
     *     tags={"Weekly Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"day_of_week"},
     *             @OA\Property(property="day_of_week", type="string", example="Wednesday"),
     *             @OA\Property(property="title", type="string", example="Chest Day"),
     *             @OA\Property(property="description", type="string", example="Afternoon stretch and core workout"),
     *             @OA\Property(property="scheduled_time", type="string", example="14:00")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Metadata updated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateMeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day_of_week' => 'required|string',
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'scheduled_time' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $plan = WeeklyPlan::firstOrCreate([
            'user_id' => $request->user()->id,
            'day_of_week' => $request->day_of_week,
        ]);

        if ($request->filled('title')) {
            $plan->title = $request->title;
        }

        if ($request->filled('description')) {
            $plan->description = $request->description;
        }

        if ($request->filled('scheduled_time')) {
            $plan->scheduled_time = $request->scheduled_time;
        }

        $plan->save();

        return response()->json(['message' => 'Metadata updated']);
    }

    /**
     * @OA\Delete(
     *     path="/api/weekly-plan/remove",
     *     summary="Remove a workout from a specific day",
     *     tags={"Weekly Plan"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"day_of_week", "workout_id"},
     *             @OA\Property(property="day_of_week", type="string", example="Tuesday"),
     *             @OA\Property(property="workout_id", type="integer", example=2)
     *         )
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

        if ($plan->items()->count() === 0) {
            $plan->delete();
        }

        return response()->json(['message' => 'Workout removed from weekly plan']);
    }
}
