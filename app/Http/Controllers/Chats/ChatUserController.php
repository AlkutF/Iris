<?php
namespace App\Http\Controllers\Chats;

use App\Models\Chat;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Notifications\MessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
class ChatUserController extends Controller
{
    public function storeMessage(Request $request, $chatId)
    {
        $message = new Message;
        $message->user_id = auth()->id();
        $message->chat_id = $chatId;
        $message->content = $request->input('content');
        $message->save();
    
        return response()->json([
            'success' => true,
            'message' => $message->content,
            'user' => [
                'nombre_perfil' => auth()->user()->profile->nombre_perfil
            ]
        ]);
    }

public function index()
{
    $user = auth()->user();

    // Obtener los amigos del usuario
    $friends = Friendship::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('friend_id', $user->id);
        })
        ->with(['user.profile', 'friend.profile'])  // Cargar la relación 'profile' de ambos usuarios
        ->get()
        ->map(function($friendship) use ($user) {
            return ($friendship->user_id == $user->id) ? $friendship->friend : $friendship->user;
        });

    // Obtener los chats del usuario con los perfiles cargados
    $chats = $user->chats()->with('users.profile')->get(); // Asegurarse de cargar los perfiles

    // Filtrar los amigos que no tienen un chat
    $friendsWithoutChat = $friends->filter(function($friend) use ($chats) {
        return !$chats->contains(function ($chat) use ($friend) {
            return $chat->users->contains($friend);
        });
    });

    return view('chats.index', compact('chats', 'friendsWithoutChat'));
}
public function create($friendId)
{
    $user = Auth::user();

    // Verificar si ya existe un chat entre estos dos usuarios
    $existingChat = Chat::whereHas('users', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->whereHas('users', function ($query) use ($friendId) {
        $query->where('user_id', $friendId);
    })->first();

    if ($existingChat) {
        // Si el chat ya existe, redirigir a ese chat en lugar de crear uno nuevo
        return redirect()->route('chats.show', $existingChat->id);
    }

    // Si no existe, crear un nuevo chat
    $chat = Chat::create([
        'name' => 'Chat entre ' . $user->name . ' y ' . User::find($friendId)->name,
    ]);

    $chat->users()->attach([$user->id, $friendId]);

    return redirect()->route('chats.show', $chat->id);
}


public function show(Chat $chat)
{
    $messages = $chat->messages()->with('user.profile')->latest()->get();

    return response()->json([
        'chat' => $chat,
        'messages' => $messages,
    ]);
}

    public function sendMessage(Request $request, $chatId)
    {
        // Validar la entrada
        $request->validate([
            'content' => 'required|string|max:255',
        ]);
    
        // Buscar el chat
        $chat = Chat::findOrFail($chatId);
        
        // Crear el mensaje
        $message = $chat->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
    
        // Retornar el mensaje
        return response()->json([
            'success' => true,
            'user' => auth()->user()->profiles->nombre_perfil,
            'message' => $message->content,
        ]);
    }

    public function getMessages($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        // Cargar la relación 'user' y también su relación 'profile'
        $messages = $chat->messages()->with('user.profile')->get();
    
        return response()->json([
            'messages' => $messages
        ]);
    }
    
}
