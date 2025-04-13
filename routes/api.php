<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkoutController;

// Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
//     return $request->user();
// });


Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [UserController::class,'getProfile']);
    Route::post('profile', [UserController::class,'saveProfile']);
    Route::post('reset-password', [UserController::class,'resetPassword']);
});


Route::get('/categories', [WorkoutController::class, 'categories']);     
Route::get('/workouts', [WorkoutController::class, 'index']);           
Route::get('/workouts/{id}', [WorkoutController::class, 'show']);       