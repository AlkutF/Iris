<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'description',
    ];

    /**
     * La relación con los usuarios.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
