<?php
namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Groups\CommentGroup; 
use App\Models\Groups\Group;
use App\Models\Groups\GroupPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class CommentGroupController  extends Controller
{
    public function store(Request $request, Group $group, GroupPost $post)
    {
        // Validar el contenido del comentario
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $comment = new CommentGroup(); // Asegúrate de tener el modelo correcto
        $comment->group_id = $group->id; // Asigna el group_id aquí
        $comment->post_id = $post->id;
        $comment->user_id = $request->user()->id;
        $comment->content = $request->input('content');
        $comment->save();
        return redirect()->route('groups.show', $group->id)->with([
            'success' => 'Reacción guardada correctamente.',
        ]);
    }
    public function edit(Group $group, CommentGroup $commentGroup)
    {
        if (auth()->id() !== $commentGroup->user_id) {
            abort(403, 'No tienes permiso para editar este comentario.');
        }
    
        return view('groups.comments.edit', compact('group', 'commentGroup'));
    }
    public function update(Request $request, Group $group, CommentGroup $commentGroup)
    {
        // Validar la entrada del comentario
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Verificar si el usuario tiene permiso para editar el comentario
        if (auth()->id() !== $commentGroup->user_id) {
            abort(403, 'No tienes permiso para editar este comentario.');
        }

        // Actualizar el comentario
        $commentGroup->update([
            'content' => $request->content,
        ]);

        return redirect()->route('groups.show', $group->id)->with('success', 'Comentario actualizado exitosamente.');
    
    }

    public function destroy(Group $group, CommentGroup $commentGroup)
    {
        // Verificar si el usuario tiene permiso para eliminar el comentario
        if (auth()->id() !== $commentGroup->user_id) {
            abort(403, 'No tienes permiso para eliminar este comentario.');
        }

        // Eliminar el comentario
        $commentGroup->delete();

        return redirect()->route('groups.show', $group->id)->with('success', 'Comentario eliminado exitosamente.');
    }
}
