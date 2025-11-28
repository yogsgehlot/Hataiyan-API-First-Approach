<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_by',
        'reported_user_id',
        'post_id',
        'comment_id',
        'reason',
        'details',
        'status',
        'processed_by',
    ];

    // User who submitted the report
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // User who is being reported
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    // Reported Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Reported Comment
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    // Admin who processed the report
    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }
}
