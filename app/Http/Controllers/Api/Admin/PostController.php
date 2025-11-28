<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $q = Post::query();

        if ($request->filled('q')) {
            $term = $request->get('q');
            $q->where('title','like',"%{$term}%")
              ->orWhere('body','like',"%{$term}%");
        }

        if ($request->filled('user_id')) {
            $q->where('user_id', $request->user_id);
        }

        $perPage = $request->get('per_page', 15);
        $posts = $q->paginate($perPage);

        return response()->json($posts);
    }

    public function show($id)
    {
        $post = Post::withTrashed()->with('user')->findOrFail($id);
        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();

        return response()->json(['message'=>'Post permanently deleted']);
    }

    public function trashed(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $posts = Post::onlyTrashed()->paginate($perPage);
        return response()->json($posts);
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return response()->json(['message'=>'Post restored', 'post'=>$post]);
    }

    // list comments (if desired)
    public function comments(Request $request)
    {
        // this depends on your app's relationships. Example:
        $perPage = $request->get('per_page', 20);
        $comments = \App\Models\Comment::paginate($perPage);
        return response()->json($comments);
    }
}
