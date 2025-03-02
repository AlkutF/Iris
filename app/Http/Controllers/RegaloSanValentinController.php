<?php

namespace App\Http\Controllers;

use App\Models\RegaloSanValentin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RegaloSanValentinController extends Controller
{
    // Mostrar todos los regalos (puedes personalizarlo según tus necesidades)
    public function index()
    {
        // Obtener todos los regalos con los datos del usuario que los creó
        $regalos = RegaloSanValentin::with(['user', 'user.profile'])->get();

        return view('admin.regalos.index', compact('regalos'));
    }

    public function destroy($id)
    {
        $regalo = RegaloSanValentin::findOrFail($id);
        
        // Verificar que el usuario autenticado sea el dueño del regalo o un admin
        if (Auth::id() !== $regalo->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('admin.regalos.index')->with('error', 'No tienes permisos para eliminar este regalo.');
        }

        $regalo->delete();

        return redirect()->route('admin.regalos')->with('success', 'Regalo eliminado correctamente.');

    }


    // Mostrar el formulario para crear un regalo
    public function create()
    {
        return view('regalos.create');
    }

    // Guardar un nuevo regalo
public function store(Request $request)
    {
        // Validación
        $request->validate([
            'nombre_pareja' => 'required|string|max:255',
            'carrera' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'anonimato' => 'required|boolean',
        ]);

        // Crear el regalo
        RegaloSanValentin::create([
            'user_id' => Auth::id(),
            'nombre_pareja' => $request->nombre_pareja,
            'carrera' => $request->carrera,
            'semestre' => $request->semestre,
            'anonimato' => $request->anonimato,
        ]);

        // Redirigir a la página del grupo después de crear el regalo
        return redirect()->route('groups.show', ['group' => $request->group_id])
            ->with('success', 'Tu regalo sera entregado el 14 de febrero ');
    }
    
    
}
