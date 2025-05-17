<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Firebase;
use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Friends",
 *     description="Operations related to managing friends"
 * )
 */
class FriendController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/friends/send",
     *     tags={"Friends"},
     *     summary="Send a friend request",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Friend request sent"),
     *     @OA\Response(response=400, description="Invalid or duplicate request"),
     * )
     */
    public function sendRequest(Request $request, Firebase $firebase)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $recipient = User::where('email', $request->email)->first();

        if ($recipient->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot add yourself'], 400);
        }

        $existing = Friend::where(function ($q) use ($request, $recipient) {
                $q->where('user_id', $request->user()->id)
                  ->where('friend_id', $recipient->id);
            })
            ->orWhere(function ($q) use ($request, $recipient) {
                $q->where('user_id', $recipient->id)
                  ->where('friend_id', $request->user()->id);
            })
            ->first();

        if ($existing) {
            if ($existing->status === 'pending') {
                return response()->json(['message' => 'Friend request already pending'], 400);
            }

            if ($existing->status === 'accepted') {
                return response()->json(['message' => 'You are already friends'], 400);
            }

            if ($existing->status === 'declined') {
                $existing->update([
                    'user_id' => $request->user()->id,
                    'friend_id' => $recipient->id,
                    'status' => 'pending',
                ]);

                return response()->json(['message' => 'Friend request re-sent']);
            }
        }

        Friend::create([
            'user_id' => $request->user()->id,
            'friend_id' => $recipient->id,
            'status' => 'pending',
        ]);

        $firebase->sendToUser($recipient, 'New message', 'You got a new message.');

        return response()->json(['message' => 'Friend request sent']);
    }

    /**
     * @OA\Post(
     *     path="/api/friends/accept/{id}",
     *     tags={"Friends"},
     *     summary="Accept a friend request",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user who sent the request",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Friend request accepted"),
     *     @OA\Response(response=404, description="Request not found")
     * )
     */
    public function acceptRequest(Request $request, $id)
    {
        $friendRequest = Friend::where('user_id', $id)
            ->where('friend_id', $request->user()->id)
            ->where('status', 'pending')
            ->first();

        if (!$friendRequest) {
            return response()->json(['message' => 'Friend request not found'], 404);
        }

        $friendRequest->update(['status' => 'accepted']);

        return response()->json(['message' => 'Friend request accepted']);
    }

    /**
     * @OA\Post(
     *     path="/api/friends/decline/{id}",
     *     tags={"Friends"},
     *     summary="Decline a friend request",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user who sent the request",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Friend request declined"),
     *     @OA\Response(response=404, description="Request not found")
     * )
     */
    public function declineRequest(Request $request, $id)
    {
        $friendRequest = Friend::where('user_id', $id)
            ->where('friend_id', $request->user()->id)
            ->where('status', 'pending')
            ->first();

        if (!$friendRequest) {
            return response()->json(['message' => 'Friend request not found'], 404);
        }

        $friendRequest->update(['status' => 'declined']);

        return response()->json(['message' => 'Friend request declined']);
    }

    /**
     * @OA\Get(
     *     path="/api/friends",
     *     tags={"Friends"},
     *     summary="List friends and friend requests",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of friends and requests")
     * )
     */
    public function list(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'friends' => $user->friends(),
            'incoming_requests' => $user->receivedFriendRequests()
                ->where('status', 'pending')
                ->with('sender:id,name,email')
                ->get()
                ->pluck('sender'),
            'outgoing_requests' => $user->sentFriendRequests()
                ->where('status', 'pending')
                ->with('receiver:id,name,email')
                ->get()
                ->pluck('receiver'),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/friends/remove/{id}",
     *     tags={"Friends"},
     *     summary="Remove a friend",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the friend to remove",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Friend removed"),
     *     @OA\Response(response=404, description="Friend not found")
     * )
     */
    public function removeFriend(Request $request, $id)
    {
        $user = $request->user();

        $deleted = Friend::where(function ($q) use ($user, $id) {
            $q->where('user_id', $user->id)->where('friend_id', $id);
        })->orWhere(function ($q) use ($user, $id) {
            $q->where('user_id', $id)->where('friend_id', $user->id);
        })->where('status', 'accepted')->delete();

        if ($deleted) {
            return response()->json(['message' => 'Friend removed']);
        } else {
            return response()->json(['message' => 'Friend not found'], 404);
        }
    }
}
