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
        // Construct a more user-friendly notification message
        $senderName = $this->message->sender->name; // Get the sender's name
        $contentPreview = $this->message->mediaType === 'text' 
            ? $this->message->content 
            : 'Sent you a media message.';

        return [
            'message' => "📩 $senderName: '$contentPreview'", // Friendly message
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message_id' => $this->message->id,
            'created_at' => $this->message->created_at,
            'mediaType' => $this->message->mediaType, // Include media type
            'media_url' => $this->message->media_url, // Include media URL if available
        ];
    }
}
