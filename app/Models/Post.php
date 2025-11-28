<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'caption',
        'media_type',
        'media_path',
        'is_published',
        'width',
        'height',
    ];

    protected $appends = ['media_url', 'aspect_ratio_class', 'likes_count', 'comments_count'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMediaUrlAttribute()
    {
        return $this->media_path ? url('storage/' . $this->media_path) : null;
    }

    public function getMediaPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
    public function getAspectRatioClassAttribute()
    {
        if (!$this->width || !$this->height) {
            return 'aspect-w-1 aspect-h-1';
        }

        // Landscape
        if ($this->width > $this->height) {
            return 'aspect-w-16 aspect-h-9';
        }

        // Portrait
        if ($this->width < $this->height) {
            return 'aspect-w-9 aspect-h-16';
        }

        // Square
        return 'aspect-w-1 aspect-h-1';
    }



}
