<?php

namespace App\Http\Controllers\RelacionUsuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlockingController extends Controller
{
    public function blockUser($userId)
    {
        $user = auth()->user();
        $blockedUser = User::find($userId);

        // Bloquear usuario
        Blocking::create([
            'user_id' => $user->id,
            'blocked_user_id' => $blockedUser->id,
        ]);

        return back()->with('message', 'Usuario bloqueado');
    }

    public function unblockUser($userId)
    {
        $user = auth()->user();
        $blockedUser = User::find($userId);

        // Desbloquear usuario
        $blocking = Blocking::where('user_id', $user->id)->where('blocked_user_id', $blockedUser->id)->first();
        $blocking->delete();

        return back()->with('message', 'Usuario desbloqueado');
    }
}
