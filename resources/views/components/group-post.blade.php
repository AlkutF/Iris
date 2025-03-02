<div class="list-group-item  post-padding" id="post-{{ $post->id }}">
    <div class="d-flex align-items-center">
        <!-- Imagen de perfil del usuario 
        @if($post->user->profile && $post->user->profile->avatar)
            <img src="{{ asset('storage/' . $post->user->profile->avatar) }}" alt="Perfil" class="rounded-circle" width="40" height="40">
        @else
            <img src="{{ asset('storage/default-avatar.png') }}" alt="Perfil" class="rounded-circle" width="40" height="40">
        @endif
	-->
        <!-- Enlace al perfil del usuario -->
        <h5 class="ml-2 font-color">
            <a href="" class="text-decoration-none">
              		Usuario Anonimo
            </a>
        </h5>

        <!-- Botones de editar y eliminar -->
        @if (auth()->check() && auth()->id() === $post->user_id)
            <div class="ml-auto flex-shrink-0 oculto">
                <a href="{{ route('posts.edit', $post->id) }}" class="text-decoration-none">
                    <i class="fas fa-edit" style="color: black"></i>
                </a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;" id="delete-form-{{ $post->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn" style="border: none; background: none; padding: 0;" 
                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="setDeletePost({{ $post->id }})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Contenido del post -->
    <div class="mt-2">
        <p id="post-content-{{ $post->id }}" class="post-content">
            <span class="post-text">
                {{ substr($post->content, 0, 1000) }}
            </span>
            <span id="more-text-{{ $post->id }}" class="more-text" style="display:none;">
                {{ substr($post->content, 1000) }}
            </span>
            <a href="javascript:void(0);" class="read-more" id="read-more-{{ $post->id }}" onclick="toggleText({{ $post->id }})" style="display:none;">
                Ver más
            </a>
        </p>
    </div>

    <!-- Medios del post (imagen o video) -->
    @if ($post->media_url)
        @php
            $mediaExtension = pathinfo($post->media_url, PATHINFO_EXTENSION);
        @endphp
        @if (in_array($mediaExtension, ['mp4', 'avi']))
            <video controls class="img-fluid mt-2">
                <source src="{{ asset('storage/' . $post->media_url) }}" type="video/{{ $mediaExtension }}">
                Tu navegador no soporta el formato de video.
            </video>
        @else
            <img src="{{ asset('storage/' . $post->media_url) }}" alt="Media" class="img-fluid mt-2">
        @endif
    @endif

    <!-- Reacciones -->
    <div class="card-footer oculto" >
        <div class="reaction-buttons">
            @csrf
            <button type="button" class="btn btn-sm btn-outline-primary react-btn" 
                    data-reaction-type="like" data-post-id="{{ $post->id }}">
                👍 <span class="icon-reaction-number" id="like-count-{{ $post->id }}">{{ $post->getReactionCountByType('like') }}</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger react-btn" 
                    data-reaction-type="love" data-post-id="{{ $post->id }}">
                ❤️ <span class="icon-reaction-number" id="love-count-{{ $post->id }}">{{ $post->getReactionCountByType('love') }}</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning react-btn" 
                    data-reaction-type="surprise" data-post-id="{{ $post->id }}">
                😲 <span class="icon-reaction-number" id="surprise-count-{{ $post->id }}">{{ $post->getReactionCountByType('surprise') }}</span>
            </button>
        </div>
    </div>

    <!-- Comentarios -->


    <!-- Formulario para añadir un comentario -->
    <form class="comment-form oculto" data-post-id="{{ $post->id }}" onsubmit="return submitComment(event, {{ $post->id }})">
        @csrf
        <div class="comment-input-container">
            <div class="comment-user-avatar">
                @if(auth()->user()->profile && auth()->user()->profile->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="Avatar" class="rounded-circle">
                @else
                    <img src="{{ asset('storage/default-avatar.png') }}" alt="Avatar" class="rounded-circle">
                @endif
            </div>
            <textarea name="content" placeholder="Comentar como {{ auth()->user()->name }}" required class="form-control comment-textarea"></textarea>
            <button type="submit" class="comment-submit-btn">
                <i class="bi bi-arrow-right-circle"></i>
            </button>
        </div>
    </form>
</div>

<script>
    // Función para alternar el texto del post
    function toggleText(postId) {
        const content = document.getElementById(`post-content-${postId}`);
        const moreText = document.getElementById(`more-text-${postId}`);
        const readMoreLink = document.getElementById(`read-more-${postId}`);
        if (moreText.style.display === "none") {
            moreText.style.display = "inline";
            readMoreLink.textContent = "Ver menos";
        } else {
            moreText.style.display = "none";
            readMoreLink.textContent = "Ver más";
        }
    }

    // Mostrar el botón "Ver más" si el contenido es largo
    document.addEventListener("DOMContentLoaded", function() {
        const postContent = "{{ $post->content }}";
        if (postContent.length > 250) {
            document.getElementById(`read-more-{{ $post->id }}`).style.display = 'inline';
        }
    });
</script>


<style>
    .oculto{
        display: none;
    }
.post-padding{
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    background-color: #ffffff;
    max-width: calc(100% - 20px);
}
.btn {
        border: 1px solid black;
        border-radius: 5px;
    }


/* Contenedor que agrupa la imagen y el textarea */
.comment-input-container {
    display: flex;
    align-items: center;
    width: 100%;
}

/* Imagen de perfil del usuario */
.comment-user-avatar {
    flex-shrink: 0;
    margin-right: 10px;
}

.comment-user-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}
/* Contenedor de los intereses */
.interests-container {
    display: flex;
    flex-wrap: wrap; /* Permite que los intereses se envuelvan si no caben en una sola línea */
    gap: 10px; /* Espacio entre los elementos */
    margin-top: 5px; /* Ajusta el margen superior si es necesario */
}

/* Cada interés */
.interest-item {
    background-color: #f1f1f1; /* Fondo gris claro */
    border-radius: 15px; /* Bordes redondeados */
    padding: 5px 10px; /* Espaciado interno */
    font-size: 14px; /* Tamaño de la fuente */
    color: #333; /* Color del texto */
    border: 1px solid #ddd; /* Borde gris claro */
    transition: background-color 0.3s ease, color 0.3s ease; /* Transición suave para el cambio de color */
}

/* Efecto hover para los intereses */
.interest-item:hover {
    background-color: var(--quaternary-color); /* Color de fondo al pasar el cursor */
    color: var(--text-button-hover); /* Color del texto al pasar el cursor */
}


textarea{
    height: 100px;

}



/* Estilo para el botón de enviar */
.comment-submit-btn {
    background-color: white;
    height: 60px;
    border: none;
    border-radius: 5px;
    display: flex;
    font-size: 20px;
    cursor: pointer;
    transition: color 0.3s ease-in-out;
    padding: 0;
}
.comment-submit-btn i {
    font-size: 30px; /* Tamaño del ícono */
    color: #007bff; /* Color del ícono */
    margin-right: 5px; /* Espacio entre el ícono y el texto */
}





/* Estilo para el botón de enviar */
.comment-submit-btn {
    margin-top: 10px;
    padding: 8px 15px;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}



.margin-top-interes{
    margin-top: -15px;
}

.icon-reaction-number{
    margin-left: 5px;
    color: black;
}
.read-more {
    color: #007bff;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
}



.reaction-buttons {
    display: flex;
    justify-content: flex-start;
    gap: 5px;
}
.list-group-item {
    margin-bottom: 15px; /* Espacio entre publicaciones */
    border: 2px solid #dee2e6; /* Borde gris claro más grueso */
    border-radius: 3    px; /* Bordes redondeados */
    background-color: #ffffff; /* Fondo blanco */
    max-width: calc(100% - 20px); /* Ancho máximo */
}
.card-footer{
    background-color: #ffffff;
}
.comment-text{
    margin-top: 5px;
    font-size: 14px;
    line-height: 1.5;
    max-width: calc(100% - 100px);
}


.react-btn {
    background-color: transparent;
    border: 1px solid;
}
/*Si quieren que vuelvan los colores , solo eliminen esto */
.react-btn {
    background-color: transparent !important;
    color: inherit !important; /* Mantiene el color original del texto */
}

.react-btn:hover, .react-btn:active, .react-btn:focus {
    background-color: transparent !important;
    color: inherit !important;
    box-shadow: none !important; /* Elimina cualquier sombra aplicada al hacer clic */
    outline: none !important; /* Elimina el contorno al hacer clic */
}
</style>

