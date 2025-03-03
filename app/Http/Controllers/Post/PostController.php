<?php

namespace App\Http\Controllers\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Interest;
use App\Models\User;
use App\Models\Reaction;
use App\Models\Story;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class PostController extends Controller
{

    public function index(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect('/'); // Redirigir a la página de inicio si no está autenticado
        }
    
        // Verificar si el usuario está baneado
        if (auth()->user()->banned_at !== null) {
            return redirect()->route('banned'); // Redirigir a la ruta de usuarios baneados
        }
    
        // Verificar si el usuario tiene perfil
        if (!auth()->user()->profile) {
            return redirect()->route('profile.create'); // Redirigir si no tiene perfil
        }
    
        // Obtener las historias con paginación
        $stories = Story::orderBy('created_at', 'desc')->paginate(4); // Paginación de 4 historias por página
        $posts = Post::orderBy('created_at', 'desc')->paginate(5); // Paginación de 5 posts por página
        
        // Obtener las notificaciones no leídas
        $unreadNotifications = auth()->user()->unreadNotifications;
    
        log::info('Notificaciones recuperadas: ', $unreadNotifications->toArray());
        if ($request->ajax()) {
            return response()->json([
                'posts' => view('components.post-list', compact('posts'))->render(),
                'has_more' => $posts->hasMorePages(), // Determinar si hay más páginas
            ]);
        }
    
        $interests = Interest::all();
    
        // Devolver la vista estándar
        return view('home', compact('posts', 'stories', 'interests', 'unreadNotifications'));
    }
    

    
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
    
        // Verificar si el post tiene un archivo multimedia y eliminarlo
        if ($post->media_url) {
            $mediaPath = public_path('storage/' . $post->media_url);
            
            // Verificar si el archivo existe y eliminarlo
            if (file_exists($mediaPath)) {
                unlink($mediaPath);  // Eliminar el archivo
                Log::info('Archivo multimedia eliminado del servidor.', ['media_path' => $mediaPath]);
            } else {
                Log::error('No se encontró el archivo multimedia en el servidor.', ['media_path' => $mediaPath]);
            }
        }
    
        // Eliminar el post
        $post->delete();
    
        // Redirigir a la página de inicio con un mensaje de éxito
        return redirect()->route('home')->with('success', 'Post eliminado con éxito');
    }

    public function edit($id)
    {
        $post = Post::with('interests')->findOrFail($id); // Recuperar los intereses asociados
        $interests = Interest::all(); // Obtener todos los intereses disponibles
        return view('posts.edit', compact('post', 'interests'));
    }
    
    public function react(Request $request, Post $post)
    {
        $user = auth()->user();
        Log::info("Usuario {$user->id} está reaccionando al post {$post->id} con tipo {$request->reaction_type}");
    
        // Verificar si el usuario ya reaccionó
        $reaction = Reaction::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->first();
    
        if ($reaction) {
            Log::info("Reacción existente encontrada, actualizando...");
            $reaction->reaction_type = $request->reaction_type;
            $saved = $reaction->save();
            Log::info($saved ? "Reacción actualizada correctamente" : "Error al actualizar la reacción");
        } else {
            Log::info("No se encontró reacción previa, creando nueva...");
            $newReaction = Reaction::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'reaction_type' => $request->reaction_type,
            ]);
    
            Log::info($newReaction ? "Reacción creada con éxito" : "Error al crear la reacción");
        }
    
        // Verificar la cantidad de notificaciones de reacciones para este post
        $notificationCount = $post->user->notifications()
            ->where('data->post_id', $post->id)
            ->where('data->type', 'post_reaction')
            ->count();
    
        Log::info("Notificaciones existentes para el post: {$notificationCount}");
    
        // Si hay menos de 2 notificaciones, enviar una nueva
        if ($notificationCount < 2) {
            Log::info("Enviando notificación al usuario {$post->user->id}");
            $post->user->notify(new PostReactionNotification($user, $post));
        } else {
            Log::info("No se envió la notificación porque ya hay 2 o más registradas");
        }
    
        // Obtener los contadores de reacciones
        $likeCount = $post->getReactionCountByType('like');
        $loveCount = $post->getReactionCountByType('love');
        $surpriseCount = $post->getReactionCountByType('surprise');
    
        Log::info("Conteo de reacciones: Like: {$likeCount}, Love: {$loveCount}, Surprise: {$surpriseCount}");
    
        return response()->json([
            'likeCount' => $likeCount,
            'loveCount' => $loveCount,
            'surpriseCount' => $surpriseCount
        ]);
    }


    public function create()
    {
        // Obtener todos los intereses
        $interests = Interest::all();
        
        return view('posts.create', compact('interests'));
    }
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'content' => 'required|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4,avi|max:5120', // Validación de multimedia
            'interests' => 'nullable|array', // Intereses seleccionados
            'interests.*' => 'exists:interests,id',
        ]);
    
        // Obtener la publicación a actualizar
        $post = Post::findOrFail($id);
    
        // Actualizar el contenido de la publicación
        $post->content = $request->content;
    
        // Manejar la eliminación de la imagen si se marca la opción
        if ($request->has('remove_media') && $post->media_url) {
            // Eliminar el archivo multimedia del almacenamiento
            Storage::delete('public/' . $post->media_url);
            
            // Eliminar la referencia a la imagen en la base de datos
            $post->media_url = null;
            $post->media_type = null;
        }
    
        // Manejar el archivo multimedia (si se sube uno nuevo)
        if ($request->hasFile('media')) {
            // Eliminar el archivo multimedia anterior si existe
            if ($post->media_url) {
                Storage::delete('public/' . $post->media_url);
            }
    
            // Subir el nuevo archivo multimedia
            $mediaPath = $request->file('media')->store('posts_media', 'public');
            $post->media_type = $request->file('media')->getClientOriginalExtension();
            $post->media_url = $mediaPath;
        }
    
        // Guardar los cambios en la publicación
        $post->save();
    
        // Asociar los intereses seleccionados con la publicación
        if ($request->has('interests')) {
            $post->interests()->sync($request->interests); // Sincroniza los intereses seleccionados
        }
    
        // Redirigir con mensaje de éxito
        return redirect()->route('posts.index')->with('success', 'Publicación actualizada correctamente.');
    }
    


    //Ruta evaluad
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4,avi|max:10240', 
                'interests' => 'nullable|array',
                'interests.*' => 'exists:interests,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    
        // Crear el post
        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
    
        // Manejar archivo multimedia
        if ($request->hasFile('media')) {
            try {
                $media = $request->file('media');
                $mediaExtension = $media->getClientOriginalExtension();
                $mediaPath = null;
    
                // Si es un video (mp4, avi)
                if (in_array($mediaExtension, ['mp4', 'avi'])) {
                    $mediaPath = $media->store('posts_media', 'public'); // Guarda en storage/app/public/posts_media
                } 
                // Si es una imagen (jpg, jpeg, png), conviértela a WebP
                elseif (in_array($mediaExtension, ['jpg', 'jpeg', 'png'])) {
                    $mediaPath = $request->file('media')->store('posts_media', 'public');
                    $post->media_type = $request->file('media')->getClientOriginalExtension();
                    $post->media_url = $mediaPath;
                    //Tuve que cambiar esto a fecha 02/03 ,perdon pero es para la prueba , no se por que no se4 sube a webp reapido 
                } else {
                    return redirect()->back()->withErrors(['media' => 'Tipo de archivo no soportado.']);
                }
    
                // Si se subió correctamente, actualiza la publicación
                if ($mediaPath) {
                    $post->update([
                        'media_type' => $mediaExtension,
                        'media_url' => $mediaPath,
                    ]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['media' => 'Error al subir el archivo: ' . $e->getMessage()]);
            }
        }
    
        // Sincronizar intereses
        if ($request->has('interests')) {
            $post->interests()->sync($request->interests);
        }
    
        return redirect()->route('posts.index');
    }
    
    // Función para convertir y almacenar imagen en WebP
    private function convertAndStoreImage($image)
    {
        $imagePath = 'posts_media/' . uniqid() . '.webp';
        $image = Image::make($image);
        
        // Guardar en storage/app/public/posts_media/
        $image->encode('webp', 75)->save(storage_path('app/public/' . $imagePath));
    
        return $imagePath;
    }
    

    public function show($postId)
    {
        $post = Post::findOrFail($postId);
        $reactionsGrouped = $post->reactions->groupBy('reaction_type')->map(function ($reactions) {
            return $reactions->map(function ($reaction) {
                return [
                    'user' => $reaction->user,
                    'reaction_type' => $reaction->reaction_type,
                ];
            });
        });
    
        $comments = $post->comments()->latest()->get(); 
        return view('posts.show', compact('post', 'comments', 'reactionsGrouped'));
    }


    
}

