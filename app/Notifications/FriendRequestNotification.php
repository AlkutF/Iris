<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FriendRequestNotification extends Notification
{
    use Queueable;

    protected $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'friend_request',
            'message' => "{$this->sender->name} te ha enviado una solicitud de amistad.",
            'sender_id' => $this->sender->id
        ];
    }
}
