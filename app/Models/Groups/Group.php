<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;
use App\Models\Interest;

class Group extends Model
{
    use HasFactory;

    // Definir los atributos que pueden ser asignados masivamente
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'type',
        'creator_id', // El ID del usuario que crea el grupo
    ];

    // Relación con los miembros del grupo
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id')
                    ->withPivot('role', 'status'); // Agrega cualquier columna adicional de la tabla pivote
    }

    // Relación con el creador del grupo
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Relación con las publicaciones del grupo
    public function posts()
    {
        return $this->hasMany(GroupPost::class, 'group_id');
    }
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_group');
    }
    
    
    
}
