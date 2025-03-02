<?php
// app/Http/Controllers/Post/CommentController.php
namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
    
        // Validar el contenido del comentario
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        // Crear y guardar el comentario
        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = auth()->user()->id;
        $comment->content = $request->content;
        $comment->save();
    
        // Obtener los comentarios actualizados, ordenados de más recientes a más antiguos
        $comments = $post->comments()->with('user')->latest()->paginate(1);
    
        // Devolver los comentarios renderizados
        $commentsHtml = view('components.comment-list', [
            'comments' => $comments,
            'post' => $post,
            'hasMore' => $comments->hasMorePages() // Verifica si hay más comentarios para cargar
        ])->render();
    
        // Devolver los comentarios renderizados como respuesta JSON
        return response()->json([
            'comments' => $commentsHtml,
            'hasMore' => $comments->hasMorePages(), // Controla la paginación
        ]);
    }
    
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        // Verifica si el usuario autenticado es el propietario del comentario
        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'No tienes permiso para eliminar este comentario.'], 403);
        }
        $comment->delete();
        return response()->json(['success' => 'Comentario eliminado exitosamente.']);
    }
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
    
        // Verificar si el usuario autenticado es el dueño del comentario
        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'No tienes permiso para editar este comentario.'], 403);
        }
    
        // Retornar la vista de edición con los datos del comentario
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        $comment = Comment::findOrFail($id);
    
        // Verificar si el usuario es el propietario del comentario
        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'No tienes permiso para editar este comentario.'], 403);
        }
    
        // Actualizar el comentario
        $comment->content = $request->content;
        $comment->save();
    
        //return redirect()->route('posts.show', $comment->post_id)->with('success', 'Comentario actualizado exitosamente.');
         return redirect()->route('home');
        //Esto enviaria directo a home , pero es una pelea con json que me niego a pelear por ahora
    }
    

    public function loadMore(Request $request, $postId)
    {
        // Obtener el post
        $post = Post::findOrFail($postId);
    
        // Obtener el número de página desde la solicitud AJAX
        $page = $request->get('page', 1);
        $commentsPerPage = 5; // Número de comentarios por carga
    
        // Obtener los comentarios paginados
        $comments = $post->comments()->with('user')->latest()->skip(($page - 1) * $commentsPerPage)->take($commentsPerPage)->get();
    
        // Verificar si hay más comentarios
        $hasMore = $post->comments()->count() > ($page * $commentsPerPage);
    
        // Devolver los comentarios como respuesta JSON
        return response()->json([
            'comments' => view('components.comment-list', compact('comments', 'post', 'hasMore'))->render(),
            'hasMore' => $hasMore, // Controlar la paginación
        ]);
    }
}