<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\Run;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Leaderboard",
 *     description="Leaderboard among friends"
 * )
 */
class LeaderboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/leaderboard/friends",
     *     tags={"Leaderboard"},
     *     summary="Get leaderboard of best runs among friends",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Leaderboard data")
     * )
     */
    public function friendsLeaderboard(Request $request)
    {
        $user = $request->user();

        // Get all friend IDs
        $friendIds = Friend::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('friend_id', $user->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->flatMap(fn($f) => [$f->user_id, $f->friend_id])
            ->unique()
            ->filter(fn($id) => $id !== $user->id)
            ->values();

        // Include current user in leaderboard
        $allUserIds = $friendIds->push($user->id);

        $leaderboard = Run::selectRaw('user_id, MAX(distance) as max_distance')
            ->whereIn('user_id', $allUserIds)
            ->groupBy('user_id')
            ->get()
            ->map(function ($row) {
                $bestRun = Run::where('user_id', $row->user_id)
                    ->where('distance', $row->max_distance)
                    ->orderByDesc('started_at')
                    ->first();

                return [
                    'user_id' => $row->user_id,
                    'name' => $bestRun->user->name ?? 'Unknown',
                    'distance' => round($bestRun->distance, 2),
                    'duration' => $bestRun->duration,
                    'started_at' => $bestRun->started_at,
                ];
            })
            ->sortByDesc('distance')
            ->values();

        return response()->json($leaderboard);
    }
}
