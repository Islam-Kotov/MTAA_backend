<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workout;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Workouts",
 *     description="Endpoints for accessing workout categories and exercises"
 * )
 */

class WorkoutController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Workouts"},
     *     summary="Get all unique workout categories",
     *     @OA\Response(
     *         response=200,
     *         description="List of exercise categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string", example="Cardio")
     *         )
     *     )
     * )
     */
    public function categories()
    {
        $categories = Workout::select('exercise_category')
            ->distinct()
            ->get()
            ->map(function ($item, $index) {
                return [
                    'id' => $index + 1,
                    'name' => $item->exercise_category
                ];
            })
            ->values();

        return response()->json($categories);
    }


    /**
     * @OA\Get(
     *     path="/api/workouts",
     *     tags={"Workouts"},
     *     summary="Get list of exercises (optionally filtered by category)",
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter exercises by category",
     *         required=false,
     *         @OA\Schema(type="string", example="Strength")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of exercises",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="exercise_name", type="string", example="Push-Up"),
     *                 @OA\Property(property="exercise_type", type="string", example="Strength"),
     *                 @OA\Property(property="exercise_photo", type="string", example="http://localhost/storage/images/pushup.jpg")
     *             )
     *         )
     *     )
     * )
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

        $baseUrl = 'http://10.0.2.2:8000';

        $workouts->transform(function ($item) use ($baseUrl) {
            if ($item->exercise_photo) {
                $item->exercise_photo = $baseUrl . '/storage/' . ltrim($item->exercise_photo, '/');
            } else {
                $item->exercise_photo = null;
            }
            return $item;
        });

        return response()->json($workouts);
    }


    /**
     * @OA\Get(
     *     path="/api/workouts/{id}",
     *     tags={"Workouts"},
     *     summary="Get detailed information about a specific exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the workout/exercise",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercise details",
     *         @OA\JsonContent(
     *             @OA\Property(property="exercise_name", type="string", example="Push-Up"),
     *             @OA\Property(property="main_muscles", type="string", example="Chest, Triceps"),
     *             @OA\Property(property="equipment_req", type="string", example="None"),
     *             @OA\Property(property="execution_guide", type="string", example="Keep your body straight..."),
     *             @OA\Property(property="exercise_photo", type="string", example="http://localhost/storage/images/pushup.jpg")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Exercise not found")
     * )
     */
    public function show($id)
    {
        $workout = Workout::find($id);

        if (!$workout) {
            return response()->json(['message' => 'Exercise not found'], 404);
        }

        $baseUrl = 'http://10.0.2.2:8000';

        return response()->json([
            'exercise_name' => $workout->exercise_name,
            'main_muscles' => $workout->main_muscles,
            'equipment_req' => $workout->equipment_req,
            'execution_guide' => $workout->execution_guide,
            'exercise_photo' => $workout->exercise_photo
                ? $baseUrl . '/storage/' . ltrim($workout->exercise_photo, '/')
                : null,
        ]);
    }

}
