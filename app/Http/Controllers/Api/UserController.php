<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
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

    public function resetPassword(Request $request)
    {
        try {
            $validatePassword = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'old_password' => 'required',
                'new_password' => 'required|confirmed'
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


    public function saveProfile(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), 
            [
                'gender' => 'required',
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
                'gender'=> $request->gender,
                'birthdate'=> $request->birthdate,
                'weight'=> $request->weight,
                'height'=> $request->height,
                'profile_completed'=> true,
            ]);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = 'avatar_' . auth()->user()->id . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('avatars', $filename, 'public');
                
                auth()->user()->photo_path = $path;
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

    public function deleteProfile(Request $request)
    {
        try {
            // $validate = Validator::make($request->all(), 
            // [
            //     'password' => 'required'
            // ]);

            // if($validate->fails()){
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'validation error',
            //         'errors' => $validate->errors()
            //     ], 422);
            // }

            $user = Auth::user();

            // if (!Hash::check($request->password, $user->password)) {
            //     return response()->json(['message' => 'Incorrect password.'], 403);
            // }

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