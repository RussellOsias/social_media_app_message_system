<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'read_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'read_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Mark the message as read by setting the read_at timestamp.
     *
     * @return void
     */
    public function markAsRead()
    {
        // Update the read_at timestamp
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if the message has been read.
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }

    /**
     * Get the latest message between two users.
     *
     * @param int $userId1
     * @param int $userId2
     * @return \App\Models\Message|null
     */
    public static function latestMessageBetween($userId1, $userId2)
    {
        return self::where(function ($query) use ($userId1, $userId2) {
                $query->where('sender_id', $userId1)
                      ->where('receiver_id', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('sender_id', $userId2)
                      ->where('receiver_id', $userId1);
            })
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
