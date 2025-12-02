<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        // Determine notifiable model: user or admin
        $user = $request->user(); // this resolves to the authenticated model (User or Admin)
        $type = get_class($user);

        // unread notifications (all)
        $unread = Notification::with('actor')  // 👈 eager load actor
            ->where('notifiable_type', $type)
            ->where('notifiable_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $read = Notification::with('actor')
            ->where('notifiable_type', $type)
            ->where('notifiable_id', $user->id)
            ->where('is_read', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();


        return response()->json([
            'unread_count' => $unread->count(),
            'unread' => $unread->map(fn($n) => $this->formatNotification($n)),
            'read' => $read->map(fn($n) => $this->formatNotification($n)),
        ]);

    }

    public function getNotification($id)
    {
       
        $notification = Notification::with(['actor:id,name'])
            ->findOrFail($id);

        // Mark as read
        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification fetched successfully',
            'notification' => [
                'id' => $notification->id,
                'type' => $notification->type,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at->diffForHumans(),

                // dynamic custom fields
                'data' => $notification->data ?? [],

                // actor info (user who triggered the notification)
                'actor' => $notification->actor ? [
                    'id' => $notification->actor->id,
                    'name' => $notification->actor->name,
                ] : null,
            ]
        ], 200);
    }


    public function markRead($id, Request $request)
    {
        $user = $request->user();
        $notification = Notification::where('id', $id)
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->firstOrFail();

        $this->service->markAsRead($notification);

        return response()->json(['success' => true, 'notification' => $notification]);
    }

    public function markAllRead(Request $request)
    {
        $user = $request->user();
        $this->service->markAllRead($user);

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {

        $userId = Auth::guard('api')->user()->id;
        $count = Notification::where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    private function formatNotification($n)
    {
        $actor = $n->actor;

        return [
            'id' => $n->id,
            'type' => $n->type,
            'is_read' => $n->is_read,
            'created_at' => $n->created_at,

            // Actor Data
            'actor_name' => $actor->name ?? $actor->username ?? 'Someone',
            'actor_username' => $actor->username ?? null,
            'actor_avatar' => $actor->avatar_path
                ? asset($actor->avatar_path)
                : asset('images/default-avatar.jpg'),

            // Your payload
            'data' => $n->data,
        ];
    }


}