<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PostReactionNotification extends Notification
{
    use Queueable;

    protected $reactor;
    protected $post;

    public function __construct($reactor, $post)
    {
        $this->reactor = $reactor;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'post_reaction',
            'message' => "{$this->reactor->name} reaccionó a tu publicación.",
            'reactor_id' => $this->reactor->id,
            'post_id' => $this->post->id
        ];
    }
}
