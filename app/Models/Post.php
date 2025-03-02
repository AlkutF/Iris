<?php

// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Interest;
use App\Models\Reaction;
use App\Models\Comment; // Asegúrate de importar el modelo Comment

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'media_type', 'media_url'];

    // Relación con el usuario (creador)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reactions()
{
    return $this->hasMany(Reaction::class);
}
    // Relación muchos a muchos con los intereses
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_post');
    }


    // Relación con los comentarios (un post tiene muchos comentarios)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // Método para obtener el conteo de reacciones de tipo específico
    public function getReactionCountByType($type)
    {
        return $this->reactions()->where('reaction_type', $type)->count();
    }
    

    
}


