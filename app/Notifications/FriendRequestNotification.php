<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class FriendRequestNotification extends Notification
{
    use Queueable;

    protected $requester;

    public function __construct($requester)
    {
        \Log::info("Creating FriendRequestNotification for user {$requester->id}"); // Log when creating notification
        $this->requester = $requester;
    }

    public function via($notifiable)
    {
        return ['database']; // Use the database for storing notifications
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->requester->name} sent you a friend request.",
            'requester_id' => $this->requester->id,
            'requester_name' => $this->requester->name,
        ];
    }
}
