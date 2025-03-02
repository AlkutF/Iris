<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;  // Para almacenar el mensaje que vamos a enviar

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Message $message
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;  // Guardamos el mensaje
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Canal de broadcast
        return new Channel('chat.' . $this->message->chat_id);  // Asumiendo que tienes un "chat_id" para identificar el chat
    }

    /**
     * Get the broadcastable data.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Datos que se envían al frontend
        return [
            'message' => $this->message->content,
            'sender' => $this->message->user->name,  // Asumiendo que cada mensaje tiene un "user" relacionado
        ];
    }
}
