<?php

namespace App\Http\Controllers;

use App\Models\Notification;
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

    public function redirect($id)
    {
        
        $response = $this->apiService->get('getNotifications/'.$id);
        // Mark as read
        // $notification->update(['is_read' => true]);
        // dd($response);
        $type = $response['data']['notification']['type'];
        $data = $response['data']['notification']['data'] ?? [];

        // Default redirect
        $fallback = redirect()->route('user.notifications');

        // Helper for safe route redirects
        $safeRedirect = function ($route, $param) use ($fallback) {
            return $param ? redirect()->route($route, $param) : $fallback;
        };

        switch ($type) {

            case 'follow':
            case 'unfollow':
                return $safeRedirect('profile.view', $notification->actor_username ?? null);

            case 'like':
            case 'mention':
            case 'report_actioned':
                return $safeRedirect('post.show', $data['post_id'] ?? null);

            case 'admin_trashed':
            case 'admin_restored':
            case 'admin_deleted':

                $targetType = $data['target_type'] ?? null;
                $targetId = $data['target_id'] ?? null;

                if (!$targetType || !$targetId) {
                    return $fallback;
                }

                if ($targetType === 'post') {
                    return $safeRedirect('post.show', $targetId);
                }

                if ($targetType === 'user') {
                    // Actor username = target user's username
                    return $safeRedirect('profile.view', $notification->actor_username ?? null);
                }

                return $fallback;
        }

        return $fallback;
    }


}
