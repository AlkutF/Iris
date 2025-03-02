<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type']; 
    // Relación con la tabla pivot chat_user
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Relación con los mensajes del chat
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
