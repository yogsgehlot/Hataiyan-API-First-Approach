<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Get logged-in user profile
    public function me(Request $request)
    {
        $userId = $request->input('id');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User ID is required',
            ], 400);
        }

        $user = User::find($userId);
        $post = Post::with(['user:id,name,username,avatar_path'])->where('user_id', $userId)->latest()->get();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'user' => $user,
            'posts' => $post
        ], 200);
    }


    // Get another user's public profile
    public function show($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        return response()->json(['status' => true, 'user' => $user]);
    }

    // Update profile
    public function update(Request $request)
    {
        // dd($request);die;   
        $userId = Auth::guard('api')->user()->id;

        $user = User::find($userId);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|alpha_dash|min:3|max:30|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'username', 'bio']);

        if ($request->hasFile('avatar')) {
            if (!empty($user->avatar_path)) {
                $relativePath = str_replace(url('/storage') . '/', '', $user->avatar_path);
                Storage::disk('public')->delete($relativePath);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $path;
        }
        // Handle cover upload
        if ($request->hasFile('cover')) {
            if (!empty($user->cover)) {
                $relativePath = str_replace(url('/storage') . '/', '', $user->cover);
                Storage::disk('public')->delete($relativePath);
            }
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path;
        }


        $user->update($data);

        return response()->json(['status' => true, 'message' => 'Profile updated successfully', 'user' => $user]);
    }

    // Soft delete user

    public function explore()
    {
        $users = User::where('id', '!=', Auth::guard('api')->user()->id)->get();

        // random posts
        $posts = Post::inRandomOrder()->take(10)->with(['user:id,name,username,avatar_path'])->get();

        return response()->json(['status' => true, 'users' => $users, 'posts' => $posts]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return response()->json(['status' => false, 'message' => 'Query parameter is required'], 400);
        }

        $users = User::where('username', 'LIKE', '%' . $query . '%')
            ->orWhere('name', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json(['status' => true, 'users' => $users]);
    }

    public function destroy($username)
    {
        $authUser = Auth::user();

        if ($authUser->username !== $username) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $authUser->delete();

        return response()->json(['status' => true, 'message' => 'Account deleted successfully']);
    }



    public function feed(Request $request, $page = 1)
    {
        $userId = Auth::guard('api')->user()->id;
        $perPage = 10;

        // IDs of users the current user follows
        $followingIds = Follow::where('follower_id', $userId)->pluck('following_id')->toArray();

        // Include yourself also in the "following" group
        $priorityOneIds = array_merge($followingIds, [$userId]);

        $posts = Post::with(['user:id,name,avatar_path'])
            ->select('posts.*')
            ->selectRaw("
            CASE
                WHEN user_id IN (" . implode(',', $priorityOneIds) . ") THEN 2
                ELSE 1
            END AS priority_score
        ")
            ->orderByDesc('priority_score') // 2 first → following + self
            ->orderBy('created_at', 'DESC') // latest first within same group
            ->when(true, function ($q) use ($priorityOneIds) {
                // Randomize ONLY the second group (non-following)
                $q->orderByRaw("
                CASE WHEN user_id NOT IN (" . implode(',', $priorityOneIds) . ")
                THEN RAND() END
            ");
            })
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    


}
