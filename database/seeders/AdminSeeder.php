<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear el administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'administrador@gmail.com',
            'password' => Hash::make('1729334373'),  // Cifra la contraseña
        ]);

        // Obtener el rol con ID 1
        $role = Role::find(1);

        // Asignar el rol al administrador
        $admin->roles()->attach($role);

        // Insertar en la tabla pivot user_roles (si es necesario)
        DB::table('user_roles')->insert([
            'user_id' => $admin->id,
            'role_id' => $role->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
