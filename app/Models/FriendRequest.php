<?php

// app/Models/FriendRequest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'receiver_id', 'status',
    ];

    /**
     * El usuario que envió la solicitud de amistad.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * El usuario que recibió la solicitud de amistad.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Verificar si una solicitud ya fue enviada por un usuario.
     */
    public static function hasSentRequest($senderId, $receiverId)
    {
        return self::where('sender_id', $senderId)
                   ->where('receiver_id', $receiverId)
                   ->where('status', 'pending')
                   ->exists();
    }

    /**
     * Aceptar una solicitud de amistad.
     */
    public static function acceptRequest($senderId, $receiverId)
    {
        $friendRequest = self::where('sender_id', $senderId)
                             ->where('receiver_id', $receiverId)
                             ->where('status', 'pending')
                             ->first();

        if ($friendRequest) {
            $friendRequest->status = 'accepted';
            $friendRequest->save();
        }
    }

    /**
     * Rechazar una solicitud de amistad.
     */
    public static function rejectRequest($senderId, $receiverId)
    {
        $friendRequest = self::where('sender_id', $senderId)
                             ->where('receiver_id', $receiverId)
                             ->where('status', 'pending')
                             ->first();

        if ($friendRequest) {
            $friendRequest->status = 'rejected';
            $friendRequest->save();
        }
    }

    /**
     * Cancelar una solicitud de amistad.
     */
    public static function cancelRequest($senderId, $receiverId)
    {
        $friendRequest = self::where('sender_id', $senderId)
                             ->where('receiver_id', $receiverId)
                             ->where('status', 'pending')
                             ->first();

        if ($friendRequest) {
            $friendRequest->delete();
        }
    }
}
