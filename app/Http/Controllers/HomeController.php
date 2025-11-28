<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        
        $userId = session('user.id');
        $perPage = 10;

        // Get following + followers lists
        $followingIds = Follow::where('follower_id', $userId)->pluck('following_id');
        $followersIds = Follow::where('following_id', $userId)->pluck('follower_id');

        // Combine priority logic
        $query = Post::with(['user:id,name,avatar_path'])
            ->when($followingIds->count(), function ($q) use ($followingIds) {
                $q->orderByRaw("FIELD(user_id, " . $followingIds->implode(',') . ") DESC");
            })
            ->when($followersIds->count(), function ($q) use ($followersIds) {
                $q->orderByRaw("FIELD(user_id, " . $followersIds->implode(',') . ") DESC");
            })
            ->orderByRaw("RAND()"); // lowest priority

        $posts = $query->paginate($perPage);
 
        return response()->json([
            'posts' => $posts
        ]);
    }
}
