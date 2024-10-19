<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import HasFactory
use Illuminate\Database\Eloquent\Model;

class GroupLike extends Model
{
    use HasFactory;

    protected $fillable = ['group_post_id', 'user_id'];

    public function post()
    {
        return $this->belongsTo(GroupPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
