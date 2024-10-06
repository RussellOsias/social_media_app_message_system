<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class FriendAcceptedNotification extends Notification
{
    use Queueable;

    protected $friend;

    public function __construct($friend)
    {
        $this->friend = $friend;
    }

    public function via($notifiable)
    {
        return ['database']; // Use the database for storing notifications
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->friend->name} is now your friend!",
            'friend_id' => $this->friend->id,
            'friend_name' => $this->friend->name,
        ];
    }
}
