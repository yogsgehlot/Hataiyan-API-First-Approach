<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Services\NotificationService;

class PostController extends Controller
{
    /**
     * List all posts (active only)
     */
    public function index(Request $request)
    {
        $query = Post::with('user');

        // Search by content
        if ($request->q) {
            $query->where('caption', 'like', "%{$request->q}%");
        }

        // Per page pagination
        $perPage = $request->per_page ?? 10;

        $posts = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'filters' => $request->only(['q', 'per_page']),
        ]);
    }


    public function show($id)
    {
        $post = Post::withTrashed()
            ->with('user')
            ->findOrFail($id);

        return view('admin.posts.show', compact('post'));
    }


    /**
     * List ONLY soft-deleted posts
     */
    public function trashed(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $posts = Post::onlyTrashed()
            ->with('user')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.posts.trashed', [
            'posts' => $posts
        ]);
    }

    /**
     * Restore a soft-deleted post
     */


    public function restore($id, NotificationService $notifications)
    {
        $admin = auth('admin')->user(); // admin guard

        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        // 🔥 Notify post owner
        if ($post->user) {
            $notifications->create(
                $post->user,
                'admin_restored',
                [
                    'target_type' => 'post',
                    'target_id' => $post->id,
                    'admin_id' => $admin->id,
                ],
                $admin
            );
        }

        return redirect()->back()->with('success', 'Post restored successfully.');
    }

    /**
     * Soft delete OR permanently delete
     */
    public function destroy($id, NotificationService $notifications)
    {
        $admin = auth('admin')->user(); // admin guard

        $post = Post::withTrashed()->findOrFail($id);

        // ----------------------------------------------------
        // 🔥 1️⃣ Permanent delete (force delete)
        // ----------------------------------------------------
        if ($post->trashed()) {

            // notify before deleting
            if ($post->user) {
                $notifications->create(
                    $post->user,
                    'admin_deleted',
                    [
                        'target_type' => 'post',
                        'target_id' => $post->id,
                        'admin_id' => $admin->id,
                    ],
                    $admin
                );
            }

            $post->forceDelete();

            return redirect()->back()->with('success', 'Post permanently deleted.');
        }

        // ----------------------------------------------------
        // 🔥 2️⃣ Soft delete (move to trash)
        // ----------------------------------------------------
        $post->delete();

        if ($post->user) {
            $notifications->create(
                $post->user,
                'admin_trashed',
                [
                    'target_type' => 'post',
                    'target_id' => $post->id,
                    'admin_id' => $admin->id,
                ],
                $admin
            );
        }

        return redirect()->back()->with('success', 'Post moved to trash.');
    }

}
