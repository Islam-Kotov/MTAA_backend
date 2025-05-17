<?php

namespace App\Http\Controllers\Api;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token'    => 'required'
        ]);

        $deviceToken = DeviceToken::where('user_id', Auth::id())->where('token', request('token'))->first();

        if (!$deviceToken) {
            DeviceToken::create([
                'user_id' => Auth::id(),
                'token' => request('token')
            ]);
        }

        return response()->json(['success' => true], 200);
    }
}
