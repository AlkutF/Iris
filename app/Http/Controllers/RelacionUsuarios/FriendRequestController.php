<?php

// app/Http/Controllers/RelacionUsuarios/FriendRequestController.php

namespace App\Http\Controllers\RelacionUsuarios;

use App\Models\FriendRequest;
use App\Models\Friendship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\FriendRequestNotification;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class FriendRequestController extends Controller
{

    public function index()
    {
        // Aquí puedes obtener y mostrar las solicitudes de amistad
        $friendRequests = auth()->user()->friendRequests;
        return view('friend_requests.index', compact('friendRequests'));
    }

    // Mostrar el perfil de usuario y el estado de la solicitud de amistad
    public function showProfile($userId)
    {

        
        $profile = User::findOrFail($userId);
        $friendRequest = FriendRequest::where(function($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', auth()->id());
        })->first();

        // Verificar si ya son amigos
        $friendship = Friendship::where(function($query) use ($userId) {
            $query->where('user_id', auth()->id())
                  ->where('friend_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('friend_id', auth()->id());
        })->first();

        return view('profile', compact('profile', 'friendRequest', 'friendship'));
    }

    public function store(Request $request)
    {
        // Validar que se envíe el ID del receptor
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);
    
        $receiver = User::findOrFail($request->receiver_id);
    
        // Prevenir enviar solicitud a uno mismo
        if ($receiver->id == auth()->id()) {
            return back()->with('error', 'No puedes enviarte una solicitud de amistad a ti mismo.');
        }
    
        // Verificar si ya existe una solicitud pendiente
        $exists = FriendRequest::where(function ($query) use ($receiver) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($receiver) {
            $query->where('sender_id', $receiver->id)
                  ->where('receiver_id', auth()->id());
        })->exists(); // Optimizamos usando `exists()`
    
        if ($exists) {
            return back()->with('error', 'Ya existe una solicitud de amistad entre ustedes.');
        }
    
        // Crear la solicitud de amistad
        FriendRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
        ]);
    
        // Enviar la notificación
        $receiver->notify(new FriendRequestNotification(auth()->user()));
        log::info('Notificación enviada');
        log::info(auth()->user());
    
        return back()->with('success', 'Solicitud de amistad enviada.');
    }

    // Aceptar una solicitud de amistad
    public function accept($user_id)
    {
        // Verificar si la solicitud de amistad existe y está pendiente
        $friendRequest = FriendRequest::where('sender_id', $user_id)
                                       ->where('receiver_id', auth()->user()->id)
                                       ->where('status', 'pending')
                                       ->first();

        if ($friendRequest) {
            $friendRequest->update(['status' => 'accepted']); // Cambiar estado a "aceptada"

            // Crear la amistad
            Friendship::create([
                'user_id' => $friendRequest->sender_id,
                'friend_id' => $friendRequest->receiver_id,
            ]);

            Friendship::create([
                'user_id' => $friendRequest->receiver_id,
                'friend_id' => $friendRequest->sender_id,
            ]);

            // Eliminar la solicitud después de ser aceptada
            $friendRequest->delete();

            return back()->with('success', 'Solicitud de amistad aceptada.');
        }

        return back()->with('error', 'Solicitud no encontrada o ya procesada.');
    }

    // Rechazar una solicitud de amistad
    public function reject($user_id)
    {
        // Verificar si la solicitud de amistad existe y está pendiente
        $friendRequest = FriendRequest::where('sender_id', $user_id)
                                       ->where('receiver_id', auth()->user()->id)
                                       ->where('status', 'pending')
                                       ->first();

        if ($friendRequest) {
            // Cambiar estado a "rechazada" y eliminar la solicitud
            $friendRequest->update(['status' => 'rejected']);
            $friendRequest->delete();

            return back()->with('success', 'Solicitud de amistad rechazada.');
        }

        return back()->with('error', 'Solicitud no encontrada.');
    }

    // Cancelar una solicitud de amistad
    public function destroy($id)
    {
        $friendRequest = FriendRequest::find($id);

        if ($friendRequest && $friendRequest->sender_id == auth()->user()->id) {
            $friendRequest->delete();
            return back()->with('success', 'Solicitud de amistad cancelada.');
        }

        return back()->with('error', 'No se pudo cancelar la solicitud de amistad.');
    }

    // Verificar si ya existe una solicitud de amistad entre dos usuarios
    public static function existingRequest($userId, $profileId)
    {
        return FriendRequest::where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->where(function ($query) use ($profileId) {
                $query->where('sender_id', $profileId)
                      ->orWhere('receiver_id', $profileId);
            })
            ->first();
    }
}
