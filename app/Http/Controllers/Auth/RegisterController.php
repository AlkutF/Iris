<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Muestra el formulario de registro
    public function showRegistrationForm()
    {
        return view('auth.register');  // Vuelve a la vista de registro
    }
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        // Si la validación falla, redirige con errores
        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput()
                ->with('registerModal', true); // Añade una bandera para abrir el modal
        }
    
        // Crear el usuario en la base de datos
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashear la contraseña
        ]);
    
        // Logear al usuario después del registro
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
    
        // Redirigir al usuario a su página de inicio después de registrarse
        return redirect('/home');
    }
}
