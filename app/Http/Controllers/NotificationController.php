<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    public function index(NotificationService $service)
    {
        $user = session('user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access your profile.');
        }

        $notifications = [];

        $response = $this->apiService->get('notifications');
        // dd($response['data']);

        if ($response['success']) {
            $notifications = $response['data'];
        }

        return view('user.notification.index', compact('notifications'));
    }
}
