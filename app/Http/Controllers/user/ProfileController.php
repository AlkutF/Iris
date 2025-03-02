<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Interest;
use Illuminate\Support\Facades\Storage;
use App\Models\Friendship;
use App\Models\Groups\Group;
use Intervention\Image\Facades\Image;//En caso de que no funcione, se debee  Habilitar GD en XAMPP 
use Illuminate\Support\Str;
use App\Models\Post;
use App\Services\FriendshipService;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Mostrar el formulario para crear el perfil
    public function create()
    {
        $interests = Interest::all(); // Obtener todos los intereses
        return view('profile.create', compact('interests'));
    }
    
    public function indexAmistades(Request $request)
    {
        $user = auth()->user();
;

        $friends = $user->friends()->paginate(10);

        if ($request->has('name')) {
            $friends = $user->friends()->where('name', 'like', '%' . $request->name . '%')->paginate(10);
           
        }

        // Verificar si la solicitud es AJAX y devolver la vista parcial de amigos
        if ($request->ajax()) {
            return response()->json([
                'users' => view('partials.friend_list', compact('friends'))->render(),
                'pagination' => view('partials.pagination', compact('friends'))->render()
            ]);
        }

        return view('amistades.index', compact('friends'));
    }
    

    // Almacenar los datos del perfil
// Almacenar los datos del perfil
        public function store(Request $request)
        {
            // Verificar si el usuario ya tiene un perfil
            if (auth()->user()->profile) {
                return redirect()->route('home')->with('error', 'Ya tienes un perfil creado.');
            }

            // Validar los datos del formulario
            $request->validate([
                'nombre_perfil' => 'required|string|max:255', // Nuevo campo obligatorio
                'bio' => 'required|string|max:255',
                'privacy' => 'required|string|in:public,private',
                'gender' => 'required|string|in:male,female,other',
                'carrera' => 'required|string|max:255', // Nueva validación para la carrera
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'interests' => 'required|array|min:1',
                'interests.*' => 'exists:interests,id',
            ], [
                'nombre_perfil.required' => 'El nombre de perfil es obligatorio.',
                'nombre_perfil.max' => 'El nombre de perfil no puede superar los 255 caracteres.',
                'bio.required' => 'La biografía es obligatoria.',
                'bio.max' => 'La biografía no puede exceder los 255 caracteres.',
                'privacy.required' => 'Debes seleccionar una opción de privacidad.',
                'privacy.in' => 'La opción de privacidad seleccionada no es válida.',
                'gender.required' => 'Debes seleccionar tu género.',
                'gender.in' => 'El género seleccionado no es válido.',
                'carrera.required' => 'Debes seleccionar una carrera.',
                'carrera.max' => 'El nombre de la carrera no puede superar los 255 caracteres.',
                'interests.required' => 'Debes seleccionar al menos un interés.',
                'interests.min' => 'Selecciona al menos un interés.',
                'interests.*.exists' => 'Uno de los intereses seleccionados no es válido.',
                'avatar.image' => 'El archivo debe ser una imagen.',
                'avatar.mimes' => 'La imagen debe estar en formato JPG, JPEG o PNG.',
                'avatar.max' => 'La imagen no puede superar los 2 MB.',
            ]);

            // Crear el perfil
            $profile = new Profile();
            $profile->user_id = auth()->id();
            $profile->nombre_perfil = $request->nombre_perfil; // Nuevo campo
            $profile->bio = $request->bio;
            $profile->privacy = $request->privacy;
            $profile->gender = $request->gender;
            $profile->carrera = $request->carrera; // Nuevo campo

            // Procesar imagen de avatar si se sube
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');

                // Convertir a WebP
                $image = Image::make($avatar);
                $avatarPath = 'avatars/' . uniqid() . '.webp';

                // Guardar la imagen en WebP en el almacenamiento
                $image->encode('webp', 80)->save(public_path('storage/' . $avatarPath));

                $profile->avatar = $avatarPath;
            } else {
                $profile->avatar = 'assets/default.webp'; // Imagen predeterminada
            }

            // Guardar el perfil en la base de datos
            $profile->save();

            // Guardar los intereses seleccionados
            if ($request->has('interests')) {
                $profile->interests()->sync($request->interests);
            }

            // Redirigir con mensaje de éxito
            return redirect()->route('home')->with('success', 'Perfil creado exitosamente.');
        }

            // Mostrar el formulario de edición de perfil
            public function edit($id)
            {
                $profile = Profile::where('user_id', $id)->firstOrFail();
                $interests = Interest::all(); // Obtener todos los intereses
                return view('profile.edit', compact('profile', 'interests'));
            }
            
            public function update(Request $request, $id)
            {
                $profile = Profile::where('user_id', $id)->firstOrFail();
            
                // Validar los datos del formulario
                $request->validate([
                    'nombre_perfil' => 'required|string|max:255',
                    'carrera' => 'nullable|string|max:255',
                    'bio' => 'nullable|string|max:255',
                    'privacy' => 'required|string',
                    'gender' => 'nullable|string',
                    'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'interests' => 'nullable|array',
                    'interests.*' => 'exists:interests,id'
                ]);
            
                // Actualizar los datos del perfil
                $profile->nombre_perfil = $request->nombre_perfil;
                $profile->carrera = $request->carrera;
                $profile->bio = $request->bio;
                $profile->privacy = $request->privacy;
                $profile->gender = $request->gender;
            
                // Si el usuario sube una nueva imagen de avatar
                if ($request->hasFile('avatar')) {
                    // Eliminar el avatar anterior si no está en 'assets'
                    if ($profile->avatar && !Str::contains($profile->avatar, 'assets/')) {
                        Storage::delete('public/' . $profile->avatar);
                    }
            
                    // Obtener la imagen subida
                    $avatar = $request->file('avatar');
            
                    // Convertir la imagen a WebP
                    $image = Image::make($avatar);
                    $avatarPath = 'avatars/' . uniqid() . '.webp';
                    $image->encode('webp', 80)->save(public_path('storage/' . $avatarPath));
            
                    // Actualizar la ruta del avatar en el perfil
                    $profile->avatar = $avatarPath;
                }
            
                // Sincronizar los intereses seleccionados
                if ($request->has('interests')) {
                    $profile->interests()->sync($request->interests);
                }
            
                // Guardar los cambios
                $profile->save();
            
                // Redirigir con un mensaje de éxito
                return redirect()->route('profile.show', ['id' => $profile->user_id])
                    ->with('success', 'Perfil actualizado correctamente');
            }
            
    protected $friendshipService;
      // Asegúrate de que FriendshipService esté correctamente inyectado
      public function __construct(FriendshipService $friendshipService)
      {
          $this->friendshipService = $friendshipService;
      }
    public function show($userId)
    {
        $profile = Profile::with('user.posts', 'interests')->where('user_id', $userId)->firstOrFail();

        // Obtener los posteos del usuario
        $posts = Post::where('user_id', $userId)->latest()->paginate(10);

        // Llama al servicio para obtener la amistad y la solicitud de amistad
        $currentUserId = auth()->id();
        $friendship = $this->friendshipService->getFriendship($currentUserId, $userId);
        $friendRequest = $this->friendshipService->getFriendRequest($currentUserId, $userId);

        return view('profile.show', compact('profile', 'posts', 'friendship', 'friendRequest'));
    }
    public function cerrarSesion()
    {
        auth()->logout(); // Cerrar sesión
        return redirect('/'); // Redirigir a la página de inicio
    }
    
}
