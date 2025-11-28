<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = Auth::guard('api')->user()->id;
        $targetId = $request->user_id;

        if ($userId == $targetId) {
            return response()->json([
                'message' => "You cannot follow yourself."
            ], 422);
        }

        $existing = Follow::where('follower_id', $userId)
            ->where('following_id', $targetId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'status' => 'unfollowed',
                'message' => "Unfollowed successfully"
            ]);
        }

        Follow::create([
            'follower_id' => $userId,
            'following_id' => $targetId
        ]);

        return response()->json([
            'status' => 'followed',
            'message' => "Followed successfully"
        ]);
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);

        $followers = $user->followers()->with('follower')->get();
        
        return response()->json([
            'count' => $followers->count(),
            'data' => $followers->map(function ($f) {
                return [
                    'id' => $f->follower->id,
                    'name' => $f->follower->name,
                    'avatar' => $f->follower->avatar_path,
                    'username' => $f->follower->username
                ];
            })
        ]);
    }

    public function following($id)
    {
        $user = User::findOrFail($id);

        $following = $user->following()->with('following')->get();
      
        return response()->json([
            'count' => $following->count(),
            'data' => $following->map(function ($f) {
                return [
                    'id' => $f->following->id,
                    'name' => $f->following->name,
                    'avatar' => $f->following->avatar_path,
                    'username' => $f->following->username
                ];
            })
        ]);
    }
}
