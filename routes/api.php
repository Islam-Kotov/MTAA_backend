<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\PredefinedWorkoutController;
use App\Http\Controllers\Api\FriendController;

// Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
//     return $request->user();
// });


Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [UserController::class,'getProfile']);
    Route::post('profile', [UserController::class,'saveProfile']);
    Route::delete('logout', [UserController::class,'logout']);
    Route::delete('delete', [UserController::class,'deleteProfile']);

    Route::post('/plan/add', [PlanController::class, 'addToPlan']);
    Route::put('/plan/update', [PlanController::class, 'updatePlanItem']);
    Route::get('/plan', [PlanController::class, 'getPlan']);
    Route::delete('/plan/remove/{workout_id}', [PlanController::class, 'removeFromPlan']);

    Route::post('reset-password', [UserController::class,'resetPassword']);

    //Friends
    Route::post('/friends/send', [FriendController::class, 'sendRequest']);         //  send
    Route::post('/friends/accept/{id}', [FriendController::class, 'acceptRequest']); // accept
    Route::post('/friends/decline/{id}', [FriendController::class, 'declineRequest']); // reject
    Route::get('/friends', [FriendController::class, 'list']); // list of friends and requests
    Route::delete('/friends/remove/{id}', [FriendController::class, 'removeFriend']); //delete

});


Route::get('/categories', [WorkoutController::class, 'categories']);     
Route::get('/workouts', [WorkoutController::class, 'index']);           
Route::get('/workouts/{id}', [WorkoutController::class, 'show']);  
// Predefined workouts (Beginner / Advanced)
Route::get('/predefined-workouts', [PredefinedWorkoutController::class, 'index']);
Route::get('/predefined-workouts/{id}', [PredefinedWorkoutController::class, 'show']);     