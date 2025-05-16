<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Challenge;

class ChallengeController extends Controller
{
    //
    // public function getCurrentChallenge()
    // {
    //     $intervalMinutes = 5; // Can change to 1440 for daily

    //     $totalChallenges = Challenge::count();
    //     if ($totalChallenges === 0) {
    //         return response()->json(null);
    //     }

    //     $minutesSinceEpoch = floor(now()->timestamp / 60);
    //     $index = floor($minutesSinceEpoch / $intervalMinutes) % $totalChallenges;

    //     $challenge = Challenge::orderBy('id')->skip($index)->first();

    //     return response()->json($challenge);
    // }
}
