<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    protected $fillable = [
        'notifiable_type', 'notifiable_id',
        'actor_type', 'actor_id',
        'type', 'data', 'is_read'
    ];

    // notifiable polymorphic relationship
    public function notifiable()
    {
        return $this->morphTo();
    }

    public function actor()
    {
        return $this->morphTo(__FUNCTION__, 'actor_type', 'actor_id');
    }
}
