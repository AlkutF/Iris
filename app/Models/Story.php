<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\StoryReaction;
class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'media',
        'text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Un story pertenece a un usuario
    }

    public function getMediaUrlAttribute()
    {
        return asset('storage/' . $this->media); // Devuelve la URL completa para acceder al archivo
    }
    public function reactions()
    {
        return $this->hasMany(StoryReaction::class); // Relación con la tabla story_reactions
    }

    // Método para obtener el conteo de reacciones por tipo
    public function getReactionCountByType($type)
    {
        return $this->StoryReaction()->where('type', $type)->count();
    }
}