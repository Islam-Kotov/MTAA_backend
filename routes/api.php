<?php


use App\Events\MessageSentEvent;
use App\Http\Controllers\Api\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\PredefinedWorkoutController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\WeeklyPlanController;
use App\Http\Controllers\RunController;
use App\Http\Controllers\Api\ChallengeController;


Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [UserController::class,'getProfile']);
    Route::post('profile', [UserController::class,'saveProfile']);
    Route::post('profile-photo', [UserController::class,'saveProfilePhoto']);
    Route::delete('logout', [UserController::class,'logout']);
    Route::delete('delete', [UserController::class,'deleteProfile']);

    Route::get('/weekly-plan', [WeeklyPlanController::class, 'index']);
    Route::post('/weekly-plan/add', [WeeklyPlanController::class, 'addWorkout']);
    Route::delete('/weekly-plan/remove', [WeeklyPlanController::class, 'removeWorkout']);
    Route::patch('/weekly-plan/update-title', [WeeklyPlanController::class, 'updateTitle']);


    Route::post('/plan/add', [PlanController::class, 'addToPlan']);
    Route::put('/plan/update', [PlanController::class, 'updatePlanItem']);
    Route::get('/plan', [PlanController::class, 'getPlan']);
    Route::delete('/plan/remove/{workout_id}', [PlanController::class, 'removeFromPlan']);

    Route::post('reset-password', [UserController::class,'resetPassword']);
    
    Route::get('media/{filepath}', [MediaController::class,'showPrivate'])->name('media.private')->where('filepath', '.*');

    //Friends
    Route::post('/friends/send', [FriendController::class, 'sendRequest']);         //  send
    Route::post('/friends/accept/{id}', [FriendController::class, 'acceptRequest']); // accept
    Route::post('/friends/decline/{id}', [FriendController::class, 'declineRequest']); // reject
    Route::get('/friends', [FriendController::class, 'list']); // list of friends and requests
    Route::delete('/friends/remove/{id}', [FriendController::class, 'removeFriend']); //delete

    Route::get('/runs', [RunController::class, 'index']);
    Route::post('/runs', [RunController::class, 'store']);

    Route::post('/devices/save', [DeviceController::class, 'store']);

    Route::get('/getCurrentChallenge', function (Request $request) {
        $challenge = app(ChallengeController::class)->getCurrentChallenge()->getData();

        broadcast(new MessageSentEvent($challenge));

        return 'ok';
    });
});


Route::get('/categories', [WorkoutController::class, 'categories']);     
Route::get('/workouts', [WorkoutController::class, 'index']);           
Route::get('/workouts/{id}', [WorkoutController::class, 'show']);  
// Predefined workouts (Beginner / Advanced)
Route::get('/predefined-workouts', [PredefinedWorkoutController::class, 'index']);
Route::get('/predefined-workouts/{id}', [PredefinedWorkoutController::class, 'show']);     
