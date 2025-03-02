<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Groups\Group;
use App\Models\Groups\GroupPost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Reaction;
use App\Models\Groups\ReactionPostGroup;
use App\Models\Groups\CommentGroup;
use App\Models\Groups\GroupPost as Post;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Log ;
class GroupPostController extends Controller
{
    // Mostrar las publicaciones de un grupo
    public function index(Group $group)
    {
        $group->load('posts'); 
        return view('groups.posts.index', compact('group'));
    }


    public function requestPost(Request $request, Group $group)
    {
        // Validar los datos recibidos
        $request->validate([
            'content' => 'required|string|max:1000',
            'media_url' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mkv|max:50000',
        ]);
    
        $mediaUrl = null;
    
        // Manejar archivo multimedia (imagen o video)
        if ($request->hasFile('media_url') && $request->file('media_url')->isValid()) {
            $file = $request->file('media_url');
            $extension = $file->getClientOriginalExtension();
            $directory = public_path("storage/grupos/{$group->id}/img");
    
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
    
            $randomFileName = Str::random(40) . '.webp'; // Nombre aleatorio con extensión .webp
    
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                // Convertir imagen a WebP
                try {
                    $image = Image::make($file);
                    $image->encode('webp', 80); // Codificar a WebP con calidad 80
                    $image->save($directory . '/' . $randomFileName); // Guardar imagen
                    $mediaUrl = 'grupos/' . $group->id . '/img/' . $randomFileName;
                } catch (\Exception $e) {
                    return back()->with('error', 'Error al guardar la imagen.');
                }
            } elseif (str_starts_with($file->getMimeType(), 'video/')) {
                // Guardar video
                $videoFileName = time() . '-' . $file->getClientOriginalName();
                $videoDirectory = public_path("storage/grupos/{$group->id}/vid");
    
                if (!file_exists($videoDirectory)) {
                    mkdir($videoDirectory, 0777, true);
                }
    
                $file->move($videoDirectory, $videoFileName);
                $mediaUrl = 'grupos/' . $group->id . '/vid/' . $videoFileName;
            }
        }
    
        // Crear la publicación
        try {
            $post = new GroupPost([
                'content' => $request->content,
                'media_url' => $mediaUrl,
                'user_id' => Auth::id(),
                'group_id' => $group->id,
                'permissions' => 0, // Siempre visible
            ]);
            $post->save();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar la publicación.');
        }
    
        // Redirigir a la página del grupo con un mensaje de éxito
        return redirect()->route('groups.show', $group)->with('success', 'La solicitud de publicación ha sido enviada con éxito.');
    }
    
    

    public function create(Group $group)
    {
        return view('groups.posts.create', compact('group'));
    }

    //Funcion evaluada , se encarga de almacenar la publicacion de un grupo en la base de datos

// Asegúrate de importar Str para generar nombres aleatorios

    public function store(Request $request, Group $group)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'media_url' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mkv|max:50000',
        ]);
    
        $mediaUrl = null;
    
        if ($request->hasFile('media_url') && $request->file('media_url')->isValid()) {
            $file = $request->file('media_url');
            $extension = $file->getClientOriginalExtension();
            $directory = public_path("storage/grupos/{$group->id}/img");
    
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
    
            // Generar un nombre aleatorio para la imagen
            $randomFileName = Str::random(40) . '.webp'; // Nombre aleatorio con extensión .webp
    
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                // Convertir la imagen a WebP
                $image = Image::make($file);
                $image->encode('webp', 80); // Codificar a WebP con calidad 80
                $image->save($directory . '/' . $randomFileName); // Guardar la imagen con el nombre aleatorio
                $mediaUrl = 'grupos/' . $group->id . '/img/' . $randomFileName;
            } elseif (str_starts_with($file->getMimeType(), 'video/')) {
                // Para videos, guardarlos directamente
                $videoFileName = time() . '-' . $file->getClientOriginalName();
                $videoDirectory = public_path("storage/grupos/{$group->id}/vid");
    
                if (!file_exists($videoDirectory)) {
                    mkdir($videoDirectory, 0777, true);
                }
    
                $file->move($videoDirectory, $videoFileName);
                $mediaUrl = 'grupos/' . $group->id . '/vid/' . $videoFileName;
            }
        }
    
        // Crear la publicación con el archivo multimedia
        $post = new GroupPost([
            'content' => $request->content,
            'media_url' => $mediaUrl,
            'user_id' => Auth::id(),
            'group_id' => $group->id,
            'permissions' => 1, // Siempre visible
        ]);
        $post->save();
    
        return redirect()->route('groups.show', $group);
    }
    
    


    public function addReaction(Request $request, Group $group, GroupPost $post)
    {
        $user = auth()->user();

        // Validar el tipo de reacción
        $request->validate([
            'reaction' => 'required|string|in:like,love,surprise',  // Asegúrate de que las reacciones sean válidas
        ]);

        // Verificar si ya existe una reacción del usuario para este post
        $existingReaction = Reaction::where('user_id', $user->id)
                                     ->where('post_id', $post->id)
                                     ->first();

        if ($existingReaction) {
            // Si ya existe una reacción, la actualizamos
            $existingReaction->reaction_type = $request->reaction;
            $existingReaction->save();
        } else {
            // Si no existe una reacción, la creamos
            Reaction::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'reaction_type' => $request->reaction,
            ]);
        }

        // Retornar la respuesta, por ejemplo, con los nuevos contadores de reacciones
        return response()->json([
            'loveCount' => $post->reactions->where('reaction_type', 'love')->count(),
            'likeCount' => $post->reactions->where('reaction_type', 'like')->count(),
            'surpriseCount' => $post->reactions->where('reaction_type', 'surprise')->count(),
        ]);
    }
    public function edit(Group $group, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403, 'No tienes permiso para editar esta publicación.');
        }

        return view('groups.posts.edit', compact('group', 'post'));
    }

    public function update(Request $request, Group $group, Post $post)
    {
        // Verificar si el usuario tiene permiso para editar la publicación
        if (auth()->id() !== $post->user_id) {
            abort(403, 'No tienes permiso para editar esta publicación.');
        }
    
        // Validar los datos del formulario, incluyendo la imagen
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar la imagen
        ]);
    
        // Si se marca la opción para eliminar la imagen
        if ($request->has('remove_image')) {
            // Eliminar la imagen anterior si existe
            if ($post->media_url && file_exists(public_path('images/posts/' . $post->media_url))) {
                unlink(public_path('images/posts/' . $post->media_url));
            }
    
            // Eliminar la imagen de la base de datos
            $post->media_url = null;
        }
    
        // Si se sube una nueva imagen, eliminar la anterior (si existe)
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($post->media_url && file_exists(public_path('images/posts/' . $post->media_url))) {
                unlink(public_path('images/posts/' . $post->media_url));
            }
    
            // Subir la nueva imagen
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/posts'), $imageName);
    
            // Actualizar la imagen en la base de datos
            $post->media_url = $imageName;
        }
    
        // Actualizar el contenido de la publicación
        $post->update([
            'content' => $request->content,
        ]);
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('groups.show', $group->id)->with('success', 'Publicación actualizada exitosamente.');
    }
    
    public function destroy(Group $group, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403, 'No tienes permiso para eliminar esta publicación.');
        }

        $post->delete();

        return redirect()->route('groups.show', $group->id)->with('success', 'Publicación eliminada exitosamente.');
    }
}
