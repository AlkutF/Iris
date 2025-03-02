<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NotificationsComponent extends Component
{
    public $unreadNotifications;
    public $readNotifications;

    /**
     * Crear una nueva instancia del componente.
     */
    public function __construct()
    {
        $user = auth()->user();

        // Obtener notificaciones no leídas
        $this->unreadNotifications = $user->unreadNotifications;

        // Obtener las últimas 10 notificaciones leídas
        $this->readNotifications = $user->notifications()
            ->whereNotNull('read_at')
            ->latest()
            ->take(10)
            ->get();
        
    }

    public function redirectToPost($postId, $notificationId)
{
    // Buscar la notificación específica
    $notification = auth()->user()->notifications()->find($notificationId);

    if ($notification) {
        // Marcar como leída
        $notification->markAsRead();
    }

    // Redirigir al detalle de la publicación
    return redirect()->route('posts.show', ['post' => $postId]);
}

    /**
     * Generar la vista del componente.
     */
    public function render()
    {
        return view('components.notifications-component');
    }
}
