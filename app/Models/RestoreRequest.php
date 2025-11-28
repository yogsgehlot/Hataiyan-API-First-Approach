<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestoreRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_type',
        'status',
        'admin_id',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
