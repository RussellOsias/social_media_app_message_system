<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $message; // Store the message instance

    public function __construct($message)
    {
        $this->message = $message; // Set the message
    }

    public function via($notifiable)
    {
        return ['database']; // Use the database for storing notifications
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "You have a new message from {$this->message->sender->name}: '{$this->message->content}'",
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message_id' => $this->message->id,
            'created_at' => $this->message->created_at,
        ];
    }
}
