<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class PostController extends Controller
{

    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    public function create()
    {
        return view('user.createPost');
    }

    public function store(Request $request)
    {
        // COMPlete: Validate and store the post

        $user = session('user');
        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to create a post.');
        }
        $validatedData = $request->validate([
            'caption' => 'nullable|string|max:1000|required_without:media',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:51200|required_without:caption',
        ]);

        $response = $this->apiService->post('posts', $validatedData);
        if ($response['success']) {
            return redirect()->route('profile')->with('success', 'Post created successfully!');
        }
        return redirect()->back()->with('error', $response['message'] ?? 'Failed to create post.')->withInput();
        
    }

    public function edit($id)
    {
        $response = $this->apiService->get("posts/{$id}");
        if ($response['success']) {
            $post = $response['data'];
            return view('user.editPost', compact('post'));
        }
        return redirect()->back()->with('error', $response['message'] ?? 'Failed to fetch post.');
    }

    public function update(Request $request, $id)
    {
        $user = session('user');
        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to update the post.');
        }

        $validatedData = $request->validate([
            'caption' => 'nullable|string|max:1000|required_without:media',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:51200|required_without:caption',
        ]);

        $response = $this->apiService->post("posts/{$id}", $validatedData);
        if ($response['success']) {
            return redirect()->route('profile')->with('success', 'Post updated successfully!');
        }
        return redirect()->back()->with('error', $response['message'] ?? 'Failed to update post.')->withInput();
    }

    public function show($id)
    { 
            
        $user = session('user');
        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to view the post.');
        }

        $response = $this->apiService->get("posts/{$id}");
        if ($response['success']) {
            $post = $response['data'];
            return view('user.showPost', compact('post'));
        }
        return redirect()->back()->with('error', $response['message'] ?? 'Failed to fetch post.');
    }

    public function destroy($id)
    {
        $user = session('user');
        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to delete the post.');
        }

        $response = $this->apiService->delete("posts/{$id}");
        if ($response['success']) {
            return redirect()->route('profile')->with('success', 'Post deleted successfully!');
        }
        return redirect()->back()->with('error', $response['message'] ?? 'Failed to delete post.');
    }
}
