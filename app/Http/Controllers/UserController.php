<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Interest; // Importar Interest model
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Excluir al usuario logueado de la consulta
        $query->where('id', '!=', Auth::id());

        // Si se recibe un parámetro de búsqueda por nombre
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Obtener los usuarios con paginación
        $users = $query->paginate(9);

        // Si es una petición AJAX, devolver solo el fragmento de la lista de usuarios
        if ($request->ajax()) {
            return view('partials.user_list', compact('users'))->render();
        }

        // Devolver la vista con los usuarios
        return view('users.index', compact('users'));
    }
}
