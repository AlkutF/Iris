<?php

namespace App\Http\Controllers\RelacionUsuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\Log;

class FriendshipController extends Controller
{

  

    //Desde aqui abajo , no se ni siquiera si uso algo
    public function showProfile($profileId)
{
    $profile = User::findOrFail($profileId);
    $posts = $profile->posts; // Suponiendo que el perfil tiene publicaciones

    // Verificar si ya son amigos o si hay solicitudes pendientes
    $friendship = Friendship::where(function ($query) use ($profileId) {
        $query->where('user_id', auth()->id())->where('friend_id', $profileId);
    })->orWhere(function ($query) use ($profileId) {
        $query->where('user_id', $profileId)->where('friend_id', auth()->id());
    })->first();

    // Verificar si hay solicitud pendiente de amistad
    $friendRequest = Friendship::where('user_id', auth()->id())
                                ->where('friend_id', $profileId)
                                ->where('status', 'pending')
                                ->first();

    return view('profile.show', compact('profile', 'posts', 'friendship', 'friendRequest'));
}
    // Verificar si existe una relación de amistad o solicitud pendiente
    public static function existingFriendship($userId, $profileId)
    {
        return Friendship::where(function($query) use ($userId, $profileId) {
                $query->where('user_id', $userId)
                      ->where('friend_id', $profileId);
            })
            ->orWhere(function($query) use ($userId, $profileId) {
                $query->where('user_id', $profileId)
                      ->where('friend_id', $userId);
            })
            ->first();
    }

    // Enviar solicitud de amistad
    public function sendRequest($userId)
    {
        $user = auth()->user();
        $friend = User::findOrFail($userId);

        // Verificar si ya existe una solicitud pendiente o una amistad
        $existingFriendship = self::existingFriendship($user->id, $friend->id);

        if ($existingFriendship) {
            // Si ya son amigos o ya hay una solicitud pendiente
            return back()->with('message', 'Ya eres amigo o hay una solicitud pendiente');
        }

        // Crear una nueva solicitud de amistad
        Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => 'pending',
        ]);

        return back()->with('message', 'Solicitud de amistad enviada');
    }

    // Aceptar solicitud de amistad
    public function acceptRequest($userId)
    {
        $user = auth()->user();

        // Buscar solicitud pendiente
        $friendship = Friendship::where('user_id', $user->id)
                                ->where('friend_id', $userId)
                                ->where('status', 'pending')
                                ->first();

        if ($friendship) {
            // Cambiar el estado de la solicitud a 'accepted'
            $friendship->update(['status' => 'accepted']);
            return back()->with('message', 'Amistad aceptada');
        }

        return back()->with('error', 'No hay ninguna solicitud pendiente');
    }

    // Rechazar solicitud de amistad
    public function rejectRequest($senderId)
    {
        $user = auth()->user();

        // Buscar solicitud pendiente
        $friendRequest = Friendship::where('sender_id', $senderId)
                                   ->where('receiver_id', $user->id)
                                   ->where('status', 'pending')
                                   ->first();

        if ($friendRequest) {
            // Eliminar la solicitud de amistad
            $friendRequest->delete();
            return back()->with('message', 'Solicitud de amistad rechazada');
        }

        return back()->with('error', 'No se pudo encontrar la solicitud de amistad');
    }

    // Eliminar una amistad
    public function destroy($id)
    {
        $friendship = Friendship::findOrFail($id);

        if ($friendship) {
            $friendship->delete();
            return back()->with('message', 'Amistad terminada correctamente');
        }

        return back()->with('error', 'No se pudo encontrar la amistad');
    }
}
