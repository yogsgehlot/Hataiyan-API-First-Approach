<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Storage;

class UserController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function profile(Request $request)
    {

        $user = session('user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access your profile.');
        }

        // Call API to get latest user details
        $response = $this->apiService->get('users/me', [
            'id' => $user['id'],
        ]);

        if ($response['success']) {
            // dd( $response['data'] );die;
            return view('user.profile', [
                'user' => $response['data']['user'] ?? $user,
                'posts' => $response['data']['posts'],
            ]);
        }

        return redirect()->back()->with('error', $response['message']);
    }

    public function edit(Request $request)
    {
        $user = session('user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access your profile.');
        }

        // Call API to get latest user details

        $response = $this->apiService->get('users/me', [
            'id' => $user['id'],
        ]);

        if ($response['success']) {
            return view('user.edit', [
                'user' => $response['data']['user'] ?? $user,
            ]);
        }

        return redirect()->back()->with('error', $response['message']);
    }

    public function update(Request $request)
    {
        $user = session('user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access your profile.');
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user['id'],
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'cover' => 'nullable|mimes:jpeg,png,jpg,gif|max:4096',
        ]);


        $response = $this->apiService->post('users/update', $validatedData);

        if ($response['success']) {
            session(['user' => $response['data']['user'] ?? $user]);

            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        }

        return redirect()->back()->with('error', $response['message'])->withInput();
    }

    public function explore(Request $request)
    {
        $user = session('user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to explore users.');
        }

        // Call API to get list of users + initial posts page
        $response = $this->apiService->get('explore');

        if ($response['success']) {
            $postsData = $response['data']['posts'] ?? null;

            return view('user.explore', [
                'users' => $response['data']['users'] ?? [],
                'posts' => $postsData ?? [],
                'nextPageUrl' => $postsData['next_page_url'] ?? null,
            ]);
        }

        return redirect()->back()->with('error', $response['message'] ?? 'Unable to load explore.');
    }

    public function loadMore(Request $request)
    {
        $page = $request->page ?? 2;
        $response = $this->apiService->get('explore', ['page' => $page]);
        // dd($response);
        return response()->json($response['data']['posts']);
    }


    public function search(Request $request)
    {
        $user = session('user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to search users.');
        }

        $query = $request->input('q', '');

        // Call API to search users
        $response = $this->apiService->get('search-users', [
            'q' => $query,
        ]);

        if ($response['success']) {
            return response()->json($response['data']['users'] ?? []);
        }

        return response()->json([], 500);
    }

    public function showUser($username)
    {
        $user = session('user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to view user profiles.');
        }

        // Call API to get user details
        $response = $this->apiService->get("users/{$username}");

        if ($response['success']) {
            return view('user.profile', [
                'user' => $response['data']['user'] ?? null,
                'posts' => $response['data']['posts'] ?? [],
            ]);
        }

        return redirect()->back()->with('error', $response['message']);
    }

}
