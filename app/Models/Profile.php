<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interest;

class Profile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'bio', 'avatar', 'privacy', 'gender','nombre_perfil','carrera'
    ];
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_profile');
    }



    public function user()
    {
        return $this->belongsTo(User::class); // Relación inversa (pertenece a un usuario)
    }
    

}

