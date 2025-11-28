<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\NotifiesMentions;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Reply;
use App\Services\NotificationService;
use Auth;
use Illuminate\Http\Request;
class PostController extends Controller
{
    use NotifiesMentions;
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $userId = $request->user_id;

        $posts = Post::with([
            'user:id,name,avatar_path',
            'likes:id,user_id,post_id',
            'comments.user:id,name,avatar_path',
            'comments.replies.user:id,name,avatar_path',
        ])
            ->where('user_id', $userId)
            ->latest()
            ->get();


        // Append is_liked to each post
        $posts->each(function ($post) use ($userId) {
            $post->is_liked = $post->isLikedBy($userId);
            $post->likes_count = $post->likes->count();
        });


        return response()->json(['status' => true, 'data' => $posts]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, NotificationService $notifications)
    {
        $request->validate([
            'caption' => 'nullable|string|max:1000|required_without:media',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:51200|required_without:caption',
        ]);

        $actor = Auth::guard('api')->user();

        $data = [
            'user_id' => $actor->id,
            'caption' => $request->caption,
            'is_published' => true,
        ];

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaType = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
            $path = $file->store('posts', 'public');

            // Only get width/height if an image
            $size = getimagesize($request->file('media'));
            $width = $size[0] ?? null;
            $height = $size[1] ?? null;

            $data['media_type'] = $mediaType;
            $data['media_path'] = $path;
            $data['width'] = $width;
            $data['height'] = $height;
        }

        $post = Post::create($data);

        // ----------------------------------
        // 🔥 Trigger mention notifications
        // ----------------------------------
        if (!empty($request->caption)) {
            $this->notifyMentionsForText(
                $request->caption,
                $actor,
                $notifications,
                [
                    'post_id' => $post->id,
                    'excerpt' => mb_substr($request->caption, 0, 150)
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully!',
            'data' => $post
        ], 201);
    }

    public function show(string $id)
    {
        $post = Post::with(
            'user:id,name,avatar_path',
            'likes:id,user_id,post_id',
            'comments.user:id,name,avatar_path',
            'comments.replies.user:id,name,avatar_path',
        )->find($id);

        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found'], 404);
        }

        $userId = Auth::guard('api')->id();

        // Add is_liked and likes_count
        $post->is_liked = $post->likes()->where('user_id', $userId)->exists();
        $post->likes_count = $post->likes->count();

        return response()->json(['status' => true, 'data' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, NotificationService $notifications)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found'], 404);
        }

        $request->validate([
            'caption' => 'nullable|string|max:1000|required_without:media',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:51200|required_without:caption',
        ]);

        $actor = $request->user();
        $oldCaption = $post->caption;       // store old caption
        $newCaption = $request->caption;    // new caption

        // Update caption
        $post->caption = $newCaption;

        // Update publish state if given
        if ($request->has('is_published')) {
            $post->is_published = $request->is_published;
        }

        // Handle media replacement
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaType = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

            // Delete old file if exists
            if ($post->media_path) {
                $relativePath = str_replace(url('/storage') . '/', '', $post->media_path);
                \Storage::disk('public')->delete($relativePath);
            }

            // Store new one
            $path = $file->store('posts', 'public');
            $post->media_type = $mediaType;
            $post->media_path = $path;
        }

        $post->save();

        // ----------------------------------------------------
        // 🔥 Trigger mention notifications ONLY if caption changed
        // ----------------------------------------------------
        if (
            !empty($newCaption) &&
            trim($oldCaption) !== trim($newCaption)    // caption is actually changed
        ) {
            $this->notifyMentionsForText(
                $newCaption,
                $actor,
                $notifications,
                [
                    'post_id' => $post->id,
                    'excerpt' => mb_substr($newCaption, 0, 150),
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully!',
            'data' => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $post = Post::find($id);
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found'], 404);
        }

        if ($post->media_path) {
            $relativePath = str_replace(url('/storage') . '/', '', $post->media_path);
            \Storage::disk('public')->delete($relativePath);
        }

        $post->delete();

        return response()->json(['status' => true, 'message' => 'Post deleted successfully!'], 200);

    }

    public function toggleLike(Request $request, NotificationService $notifications)
    {
        $actor = $request->user(); // user who is liking/unliking

        $existing = Like::where('post_id', $request->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existing) {
            // Unlike
            $existing->delete();

            return response()->json([
                'status' => true,
                'is_liked' => false,
                'likes_count' => Post::find($request->id)->likes()->count(),
                'message' => 'Like removed'
            ]);
        }

        // Like
        Like::create([
            'post_id' => $request->id,
            'user_id' => $request->user_id
        ]);

        // fetch post + owner
        $post = Post::findOrFail($request->id);
        $owner = $post->user;

        // Send notification ONLY if liking someone else's post
        if ($owner->id !== $actor->id) {
            $notifications->create(
                $owner,
                'post_liked',
                [
                    'post_id' => $post->id,
                    'post_excerpt' => substr($post->content ?? '', 0, 150)
                ],
                $actor
            );
        }

        return response()->json([
            'status' => true,
            'is_liked' => true,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    public function addComment(Request $request, $id, NotificationService $notifications)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $actor = $request->user();                     // logged-in user
        $post = Post::findOrFail($id);                // post being commented

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $actor->id,                   // always use auth user
            'body' => $request->comment
        ]);

        // ----------------------------------------------------
        // 🔥 1. Notify Post Owner (skip if commenting own post)
        // ----------------------------------------------------
        $postOwner = $post->user;

        if ($postOwner->id !== $actor->id) {
            $notifications->create(
                $postOwner,
                'comment_created',
                [
                    'post_id' => $post->id,
                    'comment_id' => $comment->id,
                    'comment_excerpt' => mb_substr($comment->body, 0, 150),
                ],
                $actor
            );
        }

        // ----------------------------------------------------
        // 🔥 2. Notify Mentioned Users in Comment Body
        // ----------------------------------------------------
        $this->notifyMentionsForText(
            $comment->body,
            $actor,
            $notifications,
            [
                'post_id' => $post->id,
                'comment_id' => $comment->id,
                'excerpt' => mb_substr($comment->body, 0, 150)
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Comment added',
            'data' => $comment
        ]);
    }
    public function reply(Request $request, NotificationService $notifications)
    {
        $request->validate([
            'reply' => 'required|string|max:500',
            'comment_id' => 'required|integer',
            'post_id' => 'required|integer',
        ]);

        $actor = $request->user();                              // logged-in user
        $parentComment = Comment::findOrFail($request->comment_id);
        $post = Post::findOrFail($request->post_id);

        // ----------------------------------------------------
        // 🔥 Create reply
        // ----------------------------------------------------
        $reply = Reply::create([
            'comment_id' => $parentComment->id,
            'post_id' => $post->id,
            'user_id' => $actor->id,                           // secure, no spoofing
            'body' => $request->reply,
        ]);

        // Prepare excerpt
        $excerpt = mb_substr($reply->body, 0, 150);

        // ----------------------------------------------------
        // 🔥 1. Notify Parent Comment Owner
        // ----------------------------------------------------
        $commentOwner = $parentComment->user;

        if ($commentOwner && $commentOwner->id !== $actor->id) {
            $notifications->create(
                $commentOwner,
                'reply_created',
                [
                    'post_id' => $post->id,
                    'comment_id' => $parentComment->id,
                    'reply_id' => $reply->id,
                    'reply_excerpt' => $excerpt,
                ],
                $actor
            );
        }

        // ----------------------------------------------------
        // 🔥 2. Notify Post Owner (only if different from actor & comment owner)
        // ----------------------------------------------------
        $postOwner = $post->user;

        if (
            $postOwner &&
            $postOwner->id !== $actor->id &&
            $postOwner->id !== $commentOwner->id
        ) {
            $notifications->create(
                $postOwner,
                'comment_created',  // or use 'reply_created' if you want
                [
                    'post_id' => $post->id,
                    'comment_id' => $reply->id,
                    'comment_excerpt' => $excerpt,
                ],
                $actor
            );
        }

        // ----------------------------------------------------
        // 🔥 3. Notify Mentioned Users (@username)
        // ----------------------------------------------------
        $this->notifyMentionsForText(
            $reply->body,
            $actor,
            $notifications,
            [
                'post_id' => $post->id,
                'comment_id' => $reply->id,
                'excerpt' => $excerpt,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Reply added',
            'data' => $reply
        ]);
    }   
}
