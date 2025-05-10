<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PredefinedWorkout;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Predefined Workouts",
 *     description="Browse and view predefined workouts by level"
 * )
 */

class PredefinedWorkoutController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/predefined-workouts",
     *     summary="Get list of predefined workouts by level",
     *     tags={"Predefined Workouts"},
     *     @OA\Parameter(
     *         name="level",
     *         in="query",
     *         required=true,
     *         description="Level of the workout (beginner or advanced)",
     *         @OA\Schema(type="string", enum={"beginner", "advanced"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of workouts",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Full Body Beginner Workout"),
     *                 @OA\Property(property="image", type="string", example="http://localhost/images/predefined/workout.jpg"),
     *                 @OA\Property(property="duration", type="integer", example=30),
     *                 @OA\Property(property="calories", type="integer", example=200),
     *                 @OA\Property(property="exercise_count", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Invalid level")
     * )
     */
    public function index(Request $request)
    {
        $level = strtolower($request->query('level'));

        if (!in_array($level, ['beginner', 'advanced'])) {
            return response()->json(['message' => 'Invalid level'], 422);
        }

        $workouts = PredefinedWorkout::where('level', $level)
            ->select('id', 'title', 'image', 'duration', 'calories', 'exercise_count')
            ->get();

        $baseUrl = 'http://192.168.1.36:8000';

        $workouts->transform(function ($workout) use ($baseUrl) {
            $workout->image = $workout->image
                ? $baseUrl . '/storage/' . ltrim($workout->image, '/')
                : null;
            return $workout;
        });

        return response()->json($workouts);
    }



    /**
     * @OA\Get(
     *     path="/api/predefined-workouts/{id}",
     *     summary="Get detailed info about a predefined workout",
     *     tags={"Predefined Workouts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the predefined workout",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workout details with exercises",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Full Body Beginner Workout"),
     *             @OA\Property(property="focus", type="string", example="Full Body"),
     *             @OA\Property(property="duration", type="integer", example=30),
     *             @OA\Property(property="calories", type="integer", example=200),
     *             @OA\Property(property="sets_reps", type="string", example="3 sets of 10 reps"),
     *             @OA\Property(property="rest", type="string", example="30 seconds"),
     *             @OA\Property(property="benefits", type="string", example="Improves endurance and strength"),
     *             @OA\Property(property="image", type="string", example="http://localhost/images/predefined/workout.jpg"),
     *             @OA\Property(
     *                 property="exercises",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="Push-Ups"),
     *                     @OA\Property(property="reps_sets", type="string", example="3 x 10"),
     *                     @OA\Property(property="description", type="string", example="Keep your back straight..."),
     *                     @OA\Property(property="image", type="string", example="http://localhost/images/predefined/exercises/pushups.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Workout not found")
     * )
     */
    public function show($id)
    {
        $workout = PredefinedWorkout::with('exercises')->find($id);

        if (!$workout) {
            return response()->json(['message' => 'Workout not found'], 404);
        }

        $baseUrl = 'http://192.168.1.36:8000';

        return response()->json([
            'title' => $workout->title,
            'focus' => $workout->focus,
            'duration' => $workout->duration,
            'calories' => $workout->calories,
            'sets_reps' => $workout->sets_reps,
            'rest' => $workout->rest,
            'benefits' => $workout->benefits,
            'image' => $workout->image
                ? $baseUrl . '/storage/' . ltrim($workout->image, '/')
                : null,
            'exercises' => $workout->exercises->map(function ($exercise) use ($baseUrl) {
                return [
                    'name' => $exercise->title,
                    'reps_sets' => $exercise->reps_sets,
                    'description' => $exercise->guide,
                    'image' => $exercise->image
                        ? $baseUrl . '/storage/' . ltrim($exercise->image, '/')
                        : null,
                ];
            }),
        ]);
    }


}
