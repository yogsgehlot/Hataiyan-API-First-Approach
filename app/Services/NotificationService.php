<?php
namespace App\Services;

use App\Models\CustomNotification;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NotificationService extends ApiService
{
    /**
     * Create a notification
     *
     * @param Model $notifiable  // instance of User or Admin
     * @param string $type       // e.g. 'post_liked'
     * @param array $data        // additional payload
     * @param Model|null $actor  // user/admin who did action, optional
     * @return Notification
     */
    public function create($notifiable, string $type, array $data = [], $actor = null)
    {
        return Notification::create([
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'actor_type' => $actor ? get_class($actor) : null,
            'actor_id' => $actor ? $actor->id : null,
            'type' => $type,
            'data' => $data,
            'is_read' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(Notification $notification)
    {
        $notification->is_read = true;
        $notification->save();
        return $notification;
    }

    /**
     * Mark all notifications for $notifiable as read
     */
    public function markAllRead($notifiable)
    {
        return Notification::where('notifiable_type', get_class($notifiable))
            ->where('notifiable_id', $notifiable->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'updated_at' => Carbon::now()]);
    }

    public function unreadCount()
    {
        // dd($this->get('/notifications/unread-count'));die;
        return $this->get('/notifications/unread-count');
    }
}
