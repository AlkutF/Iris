<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegaloSanValentin extends Model
{
    use HasFactory;

    // Especificamos la tabla si es diferente al plural del modelo
    protected $table = 'evento_san_valentin';

    // Especificamos los campos que son asignables
    protected $fillable = [
        'user_id',
        'nombre_pareja',
        'carrera',
        'semestre',
        'anonimato'
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
