<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Post;
use App\Models\Report;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function storePostReport(Request $request, $postId, NotificationService $notifications)
    {
   
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $user = auth()->user(); // Passport authenticated user
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        // Check post exists
        $post = Post::find($postId);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found.',
            ], 404);
        }

        // Prevent duplicate reports by same user
        $existing = Report::where('post_id', $postId)
            ->where('reported_by', $user->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already reported this post.',
            ], 409);
        }

        // Create report
        $report = Report::create([
            'reported_by' => $user->id,
            'reported_user_id' => $post->user_id,
            'post_id' => $postId,
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'pending',
        ]);

        // -------------------------------------------------------
        // 🔥 SEND NOTIFICATIONS
        // -------------------------------------------------------

        // 1️⃣ Notify the post owner (only if they aren't reporting themselves)
        $postOwner = $post->user;
        if ($postOwner && $postOwner->id !== $user->id) {
            $notifications->create(
                $postOwner,
                'post_reported',
                [
                    'post_id' => $post->id,
                    'report_id' => $report->id,
                    'reason' => $request->reason,
                ],
                $user   // actor = the reporting user
            );
        }

        // 2️⃣ Notify ALL admins
        $admins = Admin::all();  // or Admin::where('is_active', 1)->get()
        foreach ($admins as $admin) {
            $notifications->create(
                $admin,
                'report_created',
                [
                    'post_id' => $post->id,
                    'report_id' => $report->id,
                    'reason' => $request->reason,
                    'reported_by_id' => $user->id,
                ],
                $user
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Post reported successfully.',
            'data' => [
                'report' => $report
            ]
        ], 201);
    }

}
