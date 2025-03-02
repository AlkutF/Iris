<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;
use App\Models\Chat;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id', 'user_id', 'content'];

    // Relación con el chat
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // Relación con el usuario que envió el mensaje
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
