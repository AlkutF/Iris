<?php

namespace App\Http\Controllers\RelacionUsuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friendship;

class FriendshipController extends Controller
{

    public static function existingFriendship($userId, $profileId)
{
    return self::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })
        ->where(function($query) use ($profileId) {
            $query->where('user_id', $profileId)
                  ->orWhere('friend_id', $profileId);
        })
        ->first();
} 
    public function sendRequest($userId)
    {
        $user = auth()->user();
        $friend = User::find($userId);

        // Crear solicitud de amistad
        Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => 'pending',
        ]);

        return back()->with('message', 'Solicitud de amistad enviada');
    }

    public function acceptRequest($userId)
    {
        $user = auth()->user();
        $friendship = Friendship::where('user_id', $user->id)->where('friend_id', $userId)->first();

        // Cambiar el estado de la solicitud de amistad
        $friendship->update(['status' => 'accepted']);

        return back()->with('message', 'Amistad aceptada');
    }

    public function rejectRequest($senderId)
    {
        $user = auth()->user();
        $friendRequest = FriendRequest::where('sender_id', $senderId)
                                      ->where('receiver_id', $user->id)
                                      ->first();
    
        if ($friendRequest) {
            $friendRequest->delete();
            return back()->with('message', 'Solicitud de amistad rechazada');
        }
    
        return back()->with('error', 'No se pudo encontrar la solicitud de amistad');
    }
    
    public function destroy($id)
    {
        $friendship = Friendship::find($id);
        if ($friendship) {
            $friendship->delete();
            return redirect()->back()->with('success', 'Amistad terminada correctamente');
        }

        return redirect()->back()->with('error', 'No se pudo encontrar la amistad');
    }
}
