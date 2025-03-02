<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;

class AdminController extends Controller
{
    // Método para mostrar el panel de administración
    public function dashboard()
    {
        return view('admin.dashboard'); // Vista del panel de administración
    }

    // Método para mostrar todos los usuarios
    public function users()
    {
        // Obtener todos los usuarios
        $users = User::all();
        return view('admin.users', compact('users')); // Vista de usuarios
    }

    // Método para banear a un usuario
    public function banUser(User $user)
    {
        // Si el usuario está baneado, desbanealo (estableciendo banned_at a null)
        if ($user->banned_at) {
            $user->update(['banned_at' => null]);
            $message = 'Usuario desbaneado correctamente';
        } else {
            // Si el usuario no está baneado, banea al usuario (estableciendo banned_at a la fecha actual)
            $user->update(['banned_at' => now()]);
            $message = 'Usuario baneado correctamente';
        }
    
        // Redirigir con el mensaje de éxito
        return redirect()->route('admin.users')->with('status', $message);
    }
    public function viewUserPosts(User $user)
{
    // Obtener los posteos del usuario
    $posts = $user-> posts()->latest()->get(); // Asumiendo que la relación está definida en el modelo

    return view('admin.user_posts', compact('user', 'posts'));
}
public function deletePost(Post $post)
{
    $post->delete();

    return redirect()->back()->with('status', 'Post eliminado correctamente.');
}
    
}
