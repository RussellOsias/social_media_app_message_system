<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'content', 'media_url', 'mediaType', 'privacy', 'expires_at'
    ];

    // A story belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
