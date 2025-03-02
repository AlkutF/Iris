<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//Importamos para la sesion con GOOGLE
use Laravel\Socialite\Facades\Socialite;
//Importamos el modelo de usuario para lo GOOGLE
use App\Models\User;


class LoginController extends Controller
{
    // Muestra el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');  // Vuelve a la vista de login
    }

    // Procesa el formulario de login
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Si la autenticación es exitosa, redirige al usuario
            return redirect()->intended('/home');
        }

        // Si las credenciales son incorrectas, vuelve al formulario con un error
        return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.']);
    }


    // Redirigir al usuario a Google para la autenticación
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Manejar la respuesta de Google
    public function handleGoogleCallback()
    {
        // Obtener los datos del usuario de Google
        $googleUser = Socialite::driver('google')->user();

        // Verificar si el usuario ya existe en la base de datos
        $userExists = User::where('external_id', $googleUser->id)
                          ->where('external_auth', 'google')
                          ->first();

        // Si el usuario existe, iniciar sesión, si no, crear uno nuevo
        if ($userExists) {
            Auth::login($userExists);
        } else {
            $userNew = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar,
                'external_id' => $googleUser->id,
                'external_auth' => 'google',
            ]);
            Auth::login($userNew);
        }

        // Redirigir al usuario a la página principal
        return redirect()->route('home');
    }

    //Microsoft
    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    // Maneja el callback de Microsoft después de la autenticación
    public function handleProviderCallback()
    {
        // Obtiene la información del usuario desde Microsoft
        $user = Socialite::driver('microsoft')->stateless()->user();
    
        // Extrae el correo del usuario de la respuesta
        $email = $user->getEmail();
    
        // Si el correo no pertenece al dominio institucional, puedes rechazarlo
        $allowedDomain = 'istpet.edu.ec';  // Reemplaza con tu dominio institucional
    
        if (strpos($email, '@' . $allowedDomain) === false) {
            return redirect('/welcome')->withErrors(['error' => 'Solo se permiten correos institucionales.']);
        }
    
        // Verifica si el usuario ya existe en la base de datos
        $existingUser = User::where('email', $email)->first();
    
        // Obtener el nombre completo del usuario
        $fullName = $user->getName(); // "ALEXANDER RAFAEL MARTINEZ MORILLO"
        if ($existingUser) {
            // Si el usuario existe, puedes editar sus datos antes de iniciar sesión
            $existingUser->update([
                'name' => $fullName,  // Guarda solo el primer nombre y primer apellido
                'avatar' => $user->getAvatar(),  // Modifica el avatar si es necesario
                'external_id' => $user->getId(),  // Actualiza el ID externo de Microsoft si es necesario
            ]);
            
            // Inicia sesión con el usuario existente después de actualizarlo
            Auth::login($existingUser);
        } else {
            // Si el usuario no existe, crea uno nuevo pero con los datos modificados
            $userNew = User::create([
                'name' => $fullName, // Guarda solo el primer nombre y el primer apellido
                'email' => $user->getEmail(), // Correo electrónico
                'avatar' => $user->getAvatar(), // Avatar del usuario (si está disponible)
                'external_id' => $user->getId(), // ID único de Microsoft
                'external_auth' => 'microsoft', // Indica que la autenticación es mediante Microsoft
            ]);
    
            // Inicia sesión con el nuevo usuario creado
            Auth::login($userNew);
        }
    
        // Redirige al usuario a su página principal después del login
        return redirect()->route('home');  // Cambia la URL a la página que desees
    }
}
