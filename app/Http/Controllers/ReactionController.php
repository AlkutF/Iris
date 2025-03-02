<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostInteractionNotification;

class ReactionController extends Controller
{
    /**
     * Almacenar una reacción.
     */public function store(Request $request, Post $post)
{
    $user = auth()->user();
    
    // Validar el tipo de reacción incluyendo las nuevas reacciones 'laugh' y 'angry'
    $request->validate([
        'reaction_type' => 'required|string|in:like,love,surprise,laugh,angry', // Reacciones actualizadas
    ]);
    
    // Buscar la reacción existente del usuario al post
    $existingReaction = Reaction::where('user_id', $user->id)
                                 ->where('post_id', $post->id)
                                 ->first();

    // Si el usuario ya tiene una reacción y el tipo de reacción es el mismo, eliminamos la reacción
    if ($existingReaction && $existingReaction->reaction_type === $request->reaction_type) {
        // Eliminar la reacción del usuario
        $existingReaction->delete();
    } elseif ($existingReaction && $existingReaction->reaction_type !== $request->reaction_type) {
        // Si el tipo de reacción es diferente, actualizamos la reacción
        $existingReaction->update([
            'reaction_type' => $request->reaction_type,
        ]);
    } else {
        // Si no existe ninguna reacción, creamos una nueva
        $existingReaction = Reaction::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'reaction_type' => $request->reaction_type,
        ]);
    }
    
    // Retornar los contadores actualizados, incluyendo las nuevas reacciones
    return response()->json([
        'likeCount' => $post->reactions()->where('reaction_type', 'like')->count(),
        'loveCount' => $post->reactions()->where('reaction_type', 'love')->count(),
        'surpriseCount' => $post->reactions()->where('reaction_type', 'surprise')->count(),
        'laughCount' => $post->reactions()->where('reaction_type', 'laugh')->count(), // Nueva reacción
        'angryCount' => $post->reactions()->where('reaction_type', 'angry')->count(), // Nueva reacción
    ]);
}


    
    /**
     * Listar todas las reacciones de un post.
     */
    public function index(Post $post)
    {
        $reactions = $post->reactions()->with('user')->get();

        return response()->json([
            'reactions' => $reactions,
        ]);
    }
}
