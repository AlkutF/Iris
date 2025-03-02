<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Groups\Group;
use App\Models\Groups\GroupPost;
use App\Models\Reaction;
use App\Models\Groups\ReactionPostGroup; // Correct the import statement
use Log;

class ReactionPostGroupController extends Controller
{
    public function store(Request $request, Group $group, GroupPost $post)
    {
        // Validar la reacción (asegúrate de que el tipo de reacción sea válido)
        $request->validate([
            'reaction_type' => 'required|in:love,like,haha,surprise', // Reacciones válidas
        ]);
    
        // Comprobar si el usuario ya reaccionó a este post en este grupo
        $reaction = ReactionPostGroup::where('group_id', $group->id)
            ->where('post_id', $post->id)
            ->where('user_id', $request->user()->id)
            ->first();
    
        if ($reaction) {
            // Si ya tiene una reacción, actualizarla
            $reaction->reaction_type = $request->input('reaction_type');
            $reaction->save();
        } else {
            // Si no tiene reacción, crear una nueva
            $reaction = new ReactionPostGroup();
            $reaction->group_id = $group->id;
            $reaction->post_id = $post->id;
            $reaction->user_id = $request->user()->id;
            $reaction->reaction_type = $request->input('reaction_type');
            $reaction->save();
        }
    
        // Obtener el conteo actualizado de reacciones para cada tipo
        $reactionsCount = [
            'love' => $post->reactions()->where('reaction_type', 'love')->count(),
            'like' => $post->reactions()->where('reaction_type', 'like')->count(),
            'surprise' => $post->reactions()->where('reaction_type', 'surprise')->count(),
            'haha' => $post->reactions()->where('reaction_type', 'haha')->count(),
        ];
    
        // Devolver los resultados de forma adecuada (redirigir o actualizar con los nuevos conteos)
        // Si necesitas pasar estos valores a la vista, usa `with()`
        return redirect()->route('groups.show', $group->id)->with([
            'success' => 'Reacción guardada correctamente.',
            'reactionsCount' => $reactionsCount
        ]);
    }
}
