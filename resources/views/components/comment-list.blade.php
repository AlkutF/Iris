@foreach($comments as $comment)
    <div class="comment" id="comment-{{ $comment->id }}">
        <div class="comment-header">
            <!-- Imagen de perfil del usuario -->
            <div class="comment-user-profile">
                @if($comment->user->profile && $comment->user->profile->avatar)
                    <img src="{{ asset('storage/' . $comment->user->profile->avatar) }}" alt="Avatar" class="comment-user-avatar">
                @else
                    <img src="{{ asset('storage/default-avatar.png') }}" alt="Avatar" class="comment-user-avatar">
                @endif
            </div>

            <!-- Contenido del comentario (nombre y texto del comentario) -->
            <div class="comment-text">
                <a href="{{ route('profile.show', $comment->user->id) }}" class="comment-user-name">
                    <strong>{{ $comment->user->profile->nombre_perfil }}</strong>
                </a>
                <p class="comment-content">{{ $comment->content }}</p>
            </div>

            <!-- Si el usuario es el autor del comentario, se muestran las acciones -->
            @if ($comment->user_id == auth()->id())
    <div class="comment-actions">
        <!-- Icono para editar el comentario -->
        <a href="{{ route('comments.edit', $comment->id) }}" class="text-warning">
            <i class="fas fa-edit" style="font-size: 0.8rem;color: blue;"></i> <!-- Ícono más pequeño -->
        </a>

        <!-- Icono para eliminar el comentario -->
        <button class="btn btn-danger btn-sm delete-comment-btn" id="delete-comment-btn" data-comment-id="{{ $comment->id }}" style="border: none; background: none;">
            <i class="fas fa-trash-alt" style="color: black; font-size: 0.8rem;color: red"></i> <!-- Ícono más pequeño -->
        </button>
        
    </div>
@endif
        </div>
    </div>
@endforeach



<style>/* Contenedor del comentario */
/* Contenedor del comentario */
.comment {
    display: flex;
    flex-direction: column;
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
    margin-bottom: 15px;
}

/* Encabezado del comentario (imagen, texto, acciones) */
.comment-header {
    display: flex;
    align-items: flex-start; /* Alinea los elementos en una fila */
    width: 100%; 
}

/* Contenedor de la imagen de perfil */
.comment-user-profile {
    flex-shrink: 0;
    margin-right: 15px; /* Espacio entre avatar y texto */
}

.comment-user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

/* Contenedor del nombre y contenido del comentario */
.comment-text {
    flex-grow: 1; /* Ocupa el espacio restante */
}

/* Nombre de usuario como enlace */
.comment-user-name {
    font-weight: bold;
    font-size: 14px;
    text-decoration: none;
    color: #333;
}

/* Cambiar el color del nombre cuando el cursor pasa por encima */
.comment-user-name:hover {
    color: #007bff; /* Color de enlace al pasar el cursor */
}

/* Contenido del comentario */
.comment-content {
    margin-top: 5px;
    font-size: 14px;
    line-height: 1.5;
}

/* Contenedor de las acciones del comentario (editar, eliminar) */
.comment-actions {
    display: flex;
    gap: 10px; 
    align-items: center;
}

/* Estilo de los iconos */
.comment-actions i {
    font-size: 18px;
    cursor: pointer;
    transition: color 0.3s ease;
}

/* Cambiar color al pasar el cursor sobre los iconos */
.comment-actions i:hover {
    color: #007bff; /* Azul para el hover */
}

/* Botón de "Ver más comentarios" */
.load-more-comments {
    margin-top: 10px;
    text-align: center;
    font-size: 14px;
}

</style>