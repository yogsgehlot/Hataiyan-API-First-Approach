<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Show report form for a post
     */
    public function createPostReport($postId)
    {
        $post = Post::findOrFail($postId);

        return view('reports.report-post', [
            'post' => $post
        ]);
    }

    public function storePostReport(Request $request, $postId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        // Prepare data for API
        $payload = [
            'reason' => $request->reason,
            'details' => $request->details,
        ];

        // Call API endpoint
        $response = $this->api->post("report/post/{$postId}", $payload);

        // If API failed
        if (!$response['success']) {
            return back()
                ->with('error', $response['message'] ?? 'Unable to submit report.')
                ->withInput();
        }

        // Success
        return back()->with('success', $response['message'] ?? 'Post reported successfully.');
    }
}
