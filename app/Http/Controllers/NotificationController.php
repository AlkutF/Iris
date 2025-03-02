<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log; 
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\FriendRequestNotification;
use App\Notifications\PostReactionNotification;
use App\Notifications\FriendPostNotification;
use App\Notifications\MessageNotification;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario.
     */
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('notificaciones.index', compact('notifications'));
    }

    /**
     * Eliminar una notificación después de marcarla como leída.
     */
    public function destroy($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        $notification->delete();

        return back()->with('success', 'Notificación eliminada.');
    }

    /**
     * Obtener notificaciones no leídas y agruparlas por post_id si aplica.
     */
    public function getUnreadNotifications()
    {
        $unreadNotifications = auth()->user()->unreadNotifications;
        $unreadGroupedReactions = collect($unreadNotifications)
            ->groupBy(fn($notification) => $notification['data']['post_id'] ?? null)
            ->filter(fn($group, $postId) => $postId !== null);

        return response()->json($unreadGroupedReactions);
    }

    /**
     * Marcar una notificación como leída.
     */
    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Notificar a un usuario sobre una solicitud de amistad.
     */
    public function notifyFriendRequest($receiverId)
    {
        $receiver = User::findOrFail($receiverId);
        $receiver->notify(new FriendRequestNotification(auth()->user()));

        return response()->json(['message' => 'Notificación de solicitud de amistad enviada']);
    }

    /**
     * Redirigir a un post y marcar la notificación como leída.
     */
    public function redirectToPost($postId, $notificationId)
    {
        $post = Post::findOrFail($postId);
        $notification = auth()->user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->route('posts.show', ['post' => $postId]);
    }

    /**
     * Notificar al dueño de un post sobre una reacción.
     */
    public function notifyPostReaction($postId)
    {
        $post = Post::findOrFail($postId);
        $owner = $post->user;

        $owner->notify(new PostReactionNotification(auth()->user(), $post));

        return response()->json(['message' => 'Notificación de reacción enviada']);
    }

    /**
     * Notificar a los amigos de un usuario cuando este publique un post.
     */
    public function notifyFriendPost($postId)
    {
        $post = Post::findOrFail($postId);
        $author = $post->user;
        $friends = $author->friends(); // Asumimos que existe un método friends()

        foreach ($friends as $friend) {
            $friend->notify(new FriendPostNotification($author, $post));
        }

        return response()->json(['message' => 'Notificación enviada a los amigos']);
    }

    /**
     * Notificar a un usuario cuando recibe un mensaje.
     */
    public function notifyNewMessage($receiverId, $messageId)
    {
        $receiver = User::findOrFail($receiverId);
        $receiver->notify(new MessageNotification(auth()->user(), $messageId));

        return response()->json(['message' => 'Notificación de mensaje enviada']);
    }
}
