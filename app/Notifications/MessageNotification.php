<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageNotification extends Notification
{
    use Queueable;

    protected $sender;
    protected $message;

    public function __construct($sender, $message)
    {
        $this->sender = $sender;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // Se almacena en la base de datos
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_message',
            'message' => "{$this->sender->name} te ha enviado un mensaje.",
            'sender_id' => $this->sender->id,
            'chat_id' => $this->message->chat_id,
            'content' => $this->message->content
        ];
    }
}
