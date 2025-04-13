<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User;

class FriendController extends Controller
{
    /**
     * send request
     */
    public function sendRequest(Request $request)
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

        return response()->json(['message' => 'Friend request sent']);
    }


    /**
     * accept request
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
     * reject request
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
     * get friends list , pending , and sent requests for frienship
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
     * Remove a friend
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
