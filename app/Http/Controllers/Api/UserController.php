<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\Firebase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="User",
 *     description="Operations about user authentication and profile"
 * )
 */

class UserController extends Controller
{   
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"User"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User registered"),
     *     @OA\Response(response=401, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     *
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"User"},
     *     summary="Login user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login success"),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     *
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     tags={"User"},
     *     summary="Reset user password",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "old_password", "new_password", "new_password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="old_password", type="string", example="oldpass"),
     *             @OA\Property(property="new_password", type="string", example="newpass"),
     *             @OA\Property(property="new_password_confirmation", type="string", example="newpass")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password reset successful"),
     *     @OA\Response(response=403, description="Invalid credentials"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */


    public function resetPassword(Request $request)
    {
        try {
            $validatePassword = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'old_password' => 'required',
                'new_password' => 'required'
            ]);

            if($validatePassword->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatePassword->errors()
                ], 422);
            }

            $user = Auth::user();

            if ($user->email !== $request->email) {
                return response()->json(['message' => 'Invalid email.'], 403);
            }
        
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect.'], 403);
            }

            $user->update([
                'password'=> Hash::make($request->new_password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Password Reset Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/profile",
     *     tags={"User"},
     *     summary="Save user profile data",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"gender", "birthdate", "weight", "height"},
     *             @OA\Property(property="gender", type="string", example="male"),
     *             @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="weight", type="integer", example=70),
     *             @OA\Property(property="height", type="integer", example=180)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profile updated successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    public function saveProfile(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), 
            [
                'gender' => 'sometimes',
                'birthdate' => 'required',
                'weight'=> 'required',
                'height'=> 'required',
                'photo'=> 'sometimes|file',
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }
            
            auth()->user()->update([
                'birthdate'=> $request->birthdate,
                'weight'=> $request->weight,
                'height'=> $request->height,
                'profile_completed'=> true,
            ]);

            if ($request->has('gender')) {
                auth()->user()->update([
                    'gender'=> $request->gender,
                ]);
            }

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = 'avatar_' . auth()->user()->id . '.' . $file->getClientOriginalExtension();

                $avatarFolder = 'avatars/';
                
                $file->storeAs('users/' . auth()->user()->id . '/' . $avatarFolder, $filename, 'private');

                auth()->user()->photo_path = $avatarFolder . $filename;
                auth()->user()->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'User Profile Saved Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function saveProfilePhoto(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), 
            [
                'photo'=> 'required|file|max:5120',
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            $file = $request->file('photo');
            $filename = 'avatar_' . auth()->user()->id . '.' . $file->getClientOriginalExtension();

            $avatarFolder = 'avatars/';
            
            $file->storeAs('users/' . auth()->user()->id . '/' . $avatarFolder, $filename, 'private');

            auth()->user()->photo_path = $avatarFolder . $filename;
            auth()->user()->save();

            return response()->json([
                'status' => true,
                'message' => 'User Profile Photo Saved Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/delete",
     *     tags={"User"},
     *     summary="Delete user profile",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Profile deleted"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function deleteProfile(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), 
            [
                'password' => 'required'
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            $user = Auth::user();

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Incorrect password.'], 403);
            }

            // $user->workoutPlan()->delete();
            $user->tokens()->delete();
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Profile Deleted Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    
        /**
     * @OA\Delete(
     *     path="/api/logout",
     *     tags={"User"},
     *     summary="Logout user",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="User logged out")
     * )
     */

    public function logout(Firebase $firebase)
    {
        $firebase->sendToUser(Auth::user(), 'New message', 'You got a new message.');
        // try {
        //     $user = auth()->user();

        //     $user->tokens()->delete();

        //     return response()->json([
        //         'status'=> true,
        //         'message'=> 'User Logout Successful'
        //     ],200);

        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $th->getMessage()
        //     ], 500);
        // }
    }

        /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"User"},
     *     summary="Get user profile",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="User profile data")
     * )
     */

    public function getProfile()
    {
        return response()->json([
            'email' => auth()->user()->email,
            'name' => auth()->user()->name,
            'birthdate' => auth()->user()->birthdate ? auth()->user()->birthdate->format('Y-m-d') : null,
            'gender' => auth()->user()->gender,
            'weight' => auth()->user()->weight,
            'height' => auth()->user()->height,
            'profile_completed' => auth()->user()->profile_completed,
            'photo_url' => auth()->user()->photo_url,
        ]);
    }
}