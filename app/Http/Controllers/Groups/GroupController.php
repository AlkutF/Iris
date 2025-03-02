<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Groups\Group;
use App\Models\Groups\GroupMember;
use App\Models\Groups\GroupPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Interest;
use App\Models\User;
use Intervention\Image\Facades\Image;

class GroupController extends Controller
{
    public function verSolicitudes()
    {
        // Obtener los posteos pendientes (donde permissions es 0)
        $posts = GroupPost::where('permissions', 0)->get(); // Obtén los posteos donde no están permitidos
        return view('admin.verSolicitudes', compact('posts'));
    }
    
    public function permitirSolicitud($id)
    {
        // Buscar el post
        $post = GroupPost::findOrFail($id);
    
        // Cambiar el valor de permissions a 1 (permitido)
        $post->permissions = 1; // Cambiar a 'permitido'
        $post->save();
    
        // Redirigir con mensaje de éxito
        return redirect()->route('admin.verSolicitudes')->with('success', 'Solicitud permitida.');
    }
    
    public function denegarSolicitud($id)
    {
        // Buscar el post
        $post = GroupPost::findOrFail($id);
    
        // Eliminar el post denegado
        $post->delete();
    
        // Redirigir con mensaje de éxito
        return redirect()->route('admin.verSolicitudes')->with('success', 'Solicitud denegada y post eliminado.');
    }


    public function indexAdminGrupos()
    {
        $groups = Group::all(); // Obtener todos los grupos
        return view('admin.verGrupos', compact('groups')); // Pasar los grupos a la vista
    }
    public function index(Request $request)
    {
        $query = Group::query();
    
        // Filtrar por nombre
        if ($request->filled('name')) {
            $query->where('groups.name', 'like', '%' . $request->name . '%'); // Especifica 'groups.name'
        }
    
        // Filtrar por interés
        if ($request->filled('interest')) {
            $query->whereHas('interests', function ($q) use ($request) {
                $q->where('interests.id', $request->interest); // Especifica 'interests.id'
            });
        }
    
        // Obtener grupos paginados
        $groups = $query->with('interests')->paginate(10);
    
        // Obtener todos los intereses para el filtro
        $interests = Interest::all();
    
        return view('groups.index', compact('groups', 'interests'));
    }


    public function create()
    {
        $interests = Interest::all();
        return view('groups.create', compact('interests'));
    }

    public function join($groupId)
    {
        // Obtener el grupo
        $group = Group::findOrFail($groupId);

        // Verificar si el usuario ya es miembro del grupo
        if ($group->members->contains('user_id', auth()->user()->id)) {
            return redirect()->route('groups.show', $group->id)
                             ->with('message', 'Ya eres miembro de este grupo.');
        }

        // Si el grupo es público, el estado será 'accepted' y el rol 'member'
        if ($group->type == 'public') {
            $group->members()->attach(auth()->user()->id, [
                'status' => 'accepted',
                'role' => 'member'
            ]);
        } else {
            // Si el grupo es privado, el estado será 'pending'
            $group->members()->attach(auth()->user()->id, [
                'status' => 'pending',
                'role' => 'member',
               'created_at' => now(),  
             'updated_at' => now(),
                
            ]);
        }

        // Redirigir al usuario al grupo con un mensaje
        return redirect()->route('groups.show', $group->id)
                         ->with('message', 'Te has unido al grupo con éxito.');
    }

    public function destroy($groupId)
    {
        // Obtener el grupo
        $group = Group::findOrFail($groupId);

        // Verificar si el usuario está en el grupo
        if ($group->members()->where('user_id', auth()->user()->id)->exists()) {
            // Eliminar la relación (eliminar al usuario del grupo)
            $group->members()->detach(auth()->user()->id);

            // Redirigir con un mensaje de éxito
            return redirect()->route('groups.show', $group->id)
                             ->with('message', 'Has dejado el grupo.');
        }

        // Si el usuario no está en el grupo, redirigir con un mensaje de error
        return redirect()->route('groups.show', $group->id)
                         ->with('error', 'No estás en este grupo.');
    }
   public function destroy_request($groupId)
    {
        // Obtener el grupo
        $group = Group::findOrFail($groupId);

        // Eliminar la solicitud de unirse al grupo
        $group->members()->detach(auth()->user()->id);

        // Redirigir al usuario al grupo con un mensaje
        return redirect()->route('groups.show', $group->id)
                         ->with('message', 'Solicitud de unión eliminada.');
    }
    public function acceptRequest(Request $request, Group $group)
{
    $userId = $request->input('user_id');

    // Lógica para aceptar la solicitud del miembro
    $group->members()->updateExistingPivot($userId, ['status' => 'accepted']);

    return redirect()->back()->with('success', 'Solicitud aceptada exitosamente.');
}


public function rejectRequest(Request $request, $groupId)
{
    $userId = $request->input('user_id');
       // Lógica para rechazar la solicitud del miembro
    $group = Group::findOrFail($groupId);
    $group->members()->detach($userId);
    return redirect()->back()->with('success', 'Solicitud rechazada exitosamente.');
   
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'type' => 'required|in:public,private',
        'interests' => 'nullable|array',
        'interests.*' => 'exists:interests,id',
    ]);

    // Subir la imagen si se proporciona
    $imagePath = null;
    if ($request->hasFile('image')) {
        // Agregar log para verificar si la imagen es recibida
        Log::info('Imagen recibida', ['filename' => $request->file('image')->getClientOriginalName()]);
        
        // Convertir y guardar la imagen en formato WebP
        $imagePath = $this->convertAndStoreImage($request->file('image'));
        
        // Agregar log para verificar el path de la imagen después de la conversión
        Log::info('Imagen convertida y guardada', ['image_path' => $imagePath]);
    } else {
        Log::warning('No se ha recibido ninguna imagen.');
    }

    // Crear el grupo
    $group = Group::create([
        'name' => $request->name,
        'description' => $request->description,
        'image_url' => $imagePath ? 'storage/' . $imagePath : null, 
        'type' => $request->type,
        'creator_id' => Auth::id(),
    ]);

    // Registrar al creador como miembro del grupo con rol de admin
    GroupMember::create([
        'group_id' => $group->id,
        'user_id' => Auth::id(),
        'role' => 'admin',
        'status' => 'accepted',
    ]);

    if ($request->has('interests')) {
        $group->interests()->attach($request->interests); // Asocia los intereses seleccionados
        Log::info('Intereses asociados al grupo', ['interests' => $request->interests]);
    }

    // Redirigir a la vista del grupo recién creado
    return redirect()->route('groups.show', $group->id);
}

private function convertAndStoreImage($image)
{
    try {
        // Generar un nombre único para la imagen
        $imagePath = 'group_images/' . uniqid() . '.webp';

        // Log para ver el nombre del archivo antes de la conversión
        Log::info('Convirtiendo imagen', ['original_name' => $image->getClientOriginalName()]);

        // Usar Intervention Image para hacer la conversión a WebP
        $image = Image::make($image);
        $image->encode('webp', 75); // Establecer la calidad de la imagen (0-100)

        // Guardar la imagen convertida en el directorio adecuado
        $image->save(public_path('storage/' . $imagePath));

        // Log para confirmar que la imagen se guardó correctamente
        Log::info('Imagen convertida y guardada en el directorio', ['path' => public_path('storage/' . $imagePath)]);

        return $imagePath;
    } catch (\Exception $e) {
        // Log en caso de error durante la conversión o guardado
        Log::error('Error al procesar la imagen', ['error' => $e->getMessage()]);
        return null;
    }
}
public function show($id)
{
    $user = auth()->user();
    $UrlAvatar = $user->profile ? $user->profile->avatar : 'default-avatar.png';
    $group = Group::with([
        'members' => function ($query) {
            $query->withPivot('role', 'status');
        },
        'posts.user', // Relación para obtener los posts con el usuario que los creó
        'interests'
    ])->findOrFail($id);
    $filteredPosts = $group->posts()
    ->where('permissions', 1)
    ->select('id', 'group_id', 'user_id', 'content', 'media_url', 'permissions')
    ->with('user') // Cargar la relación user
    ->get();
    $comments = $group->comments;
    $isAdmin = $group->creator_id == auth()->id();
    $post = $filteredPosts->first();
    return view('groups.show', compact('group', 'isAdmin', 'UrlAvatar', 'post', 'comments', 'filteredPosts'));
}

    public function promoteToAdmin(Request $request, $groupId)
{
    $group = Group::findOrFail($groupId);
    
    // Verificar si el usuario autenticado es el creador del grupo
    if ($group->creator_id != auth()->id()) {
        return redirect()->route('groups.show', $group->id)->with('error', 'No tienes permiso para promover a admin.');
    }

    // Buscar al miembro que será promovido a admin
    $member = $group->members()->where('user_id', $request->user_id)->first();
    
    if ($member) {
        // Actualizar el rol del miembro a "admin"
        $member->pivot->update(['role' => 'admin']);
        
        return redirect()->route('groups.show', $group->id)->with('success', 'Miembro promovido a admin.');
    }

    return redirect()->route('groups.show', $group->id)->with('error', 'Miembro no encontrado.');
}



        public function demoteToMember(Request $request, $groupId)
        {
            $group = Group::findOrFail($groupId);
            if ($group->creator_id != auth()->id()) {
                return redirect()->route('groups.show', $group->id)->with('error', 'No tienes permiso para realizar esta acción.');
            }
            $member = $group->members()->where('user_id', $request->user_id)->first();
            if ($member && $member->pivot->role == 'admin') {
                // Rebajar a miembro
                $group->members()->updateExistingPivot($request->user_id, ['role' => 'member']);
                return redirect()->route('groups.show', $group->id)->with('success', 'Miembro rebajado a miembro.');
            }
            return redirect()->route('groups.show', $group->id)->with('error', 'No se pudo rebajar al miembro.');
        } 

    
        public function removeMember(Request $request, $groupId)
        {
            $group = Group::findOrFail($groupId);
        
            // Verificar si el usuario autenticado es el creador del grupo o tiene permisos para eliminar miembros
            if ($group->creator_id != auth()->id()) {
                return redirect()->route('groups.show', $group->id)->with('error', 'No tienes permiso para eliminar miembros.');
            }
        
            // Buscar al miembro que será eliminado
            $userId = $request->input('user_id');
            $member = $group->members()->where('user_id', $userId)->first();
        
            if ($member) {
                // Eliminar al miembro del grupo
                $group->members()->detach($userId);
        
                return redirect()->route('groups.show', $group->id)->with('success', 'Miembro eliminado del grupo.');
            }
        
            return redirect()->route('groups.show', $group->id)->with('error', 'Miembro no encontrado.');
        }
        



    public function edit($groupId)
{
    $group = Group::findOrFail($groupId);

    // Verifica si el usuario es el creador del grupo
    if ($group->creator_id != auth()->id()) {
        return redirect()->route('groups.show', $groupId)->with('error', 'No tienes permiso para editar este grupo.');
    }

    return view('groups.edit', compact('group'));
}



public function update(Request $request, $groupId)
{
    $group = Group::findOrFail($groupId);

    // Verifica si el usuario es el creador del grupo
    if ($group->creator_id != auth()->id()) {
        return redirect()->route('groups.show', $groupId)->with('error', 'No tienes permiso para editar este grupo.');
    }

    // Valida los datos
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'type' => 'required|in:public,private',
        'image' => 'nullable|image|max:2048', // Opcional: subir imagen
    ]);

    // Actualiza los datos del grupo
    $group->name = $validatedData['name'];
    $group->description = $validatedData['description'];
    $group->type = $validatedData['type'];

    // Si se sube una nueva imagen, guárdala
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('group_images', 'public');
        $group->image_url = $path;
    }

    $group->save();

    return redirect()->route('groups.show', $groupId)->with('success', 'Grupo actualizado exitosamente.');
}

public function destroyGroup($groupId)
{
    $group = Group::findOrFail($groupId);

    // Verifica si el usuario es el creador del grupo
    if ($group->creator_id != auth()->id()) {
        return redirect()->route('groups.show', $groupId)->with('error', 'No tienes permiso para eliminar este grupo.');
    }

    $group->delete();

    return redirect()->route('home')->with('success', 'Grupo eliminado exitosamente.');
}
}
