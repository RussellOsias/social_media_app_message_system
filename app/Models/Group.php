<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import HasFactory
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory; // Use HasFactory trait

    protected $fillable = [
        'name', 'description', 'owner_id',
    ];

    // Relationship to users (members of the group)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Relationship to the owner of the group
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship to posts in the group
    public function posts()
    {
        return $this->hasMany(GroupPost::class); // Use GroupPost model
    }

    public function pendingRequests()
    {
        return $this->hasMany(PendingRequest::class); // Assuming you have a PendingRequest model
    }
}
