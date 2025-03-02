<head>
    <!-- Otras etiquetas meta y enlaces -->
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    
    <!-- Bootstrap CSS (si no lo has incluido ya) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<ul class="list-group" id="posts-list">
    @foreach($posts as $post)
        <li class="list-group-item" id="post-{{ $post->id }}">
            <div class="d-flex align-items-center">
                <!-- Mostrar la imagen de perfil del usuario desde la tabla 'profiles' -->
                @if($post->user->profile && $post->user->profile->avatar)
                    <img src="{{ asset('storage/' . $post->user->profile->avatar) }}" alt="Perfil" class="rounded-circle" width="40" height="40">
                @else
                    <img src="{{ asset('storage/default-avatar.png') }}" alt="Perfil" class="rounded-circle" width="40" height="40">
                @endif

                <!-- Enlazar el nombre del usuario al perfil -->
                <h5 class="ml-2 font-color">
                    <a href="{{ url('/profile/' . $post->user->id) }}" class="text-decoration-none">
                        {{ $post->user->profile->nombre_perfil }}
                    </a>
                </h5>

                <!-- Botones de editar y eliminar a la derecha -->
                @if (auth()->check() && auth()->user()->id === $post->user_id)
                    <div class="ml-auto flex-shrink-0">
                        <!-- Botón de editar (icono) -->
                        <a href="{{ route('posts.edit', $post->id) }}" class="text-decoration-none">
                            <i class="fas fa-edit"  ></i>
                        </a>

                        <!-- Formulario para eliminar el post -->
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;" id="delete-form-{{ $post->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="" style="border: none; background: none; padding: 0;" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="setDeletePost({{ $post->id }})">
                                <i class="fas fa-trash-alt" style="color: red"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <div class="mt-2">
                <p id="post-content-{{ $post->id }}" class="post-content">
                    <span class="post-text">
                        {{ substr($post->content, 0, 500) }}
                    </span>
                    <span id="more-text-{{ $post->id }}" class="more-text" style="display:none;">
                        {{ substr($post->content, 500) }} 
                    </span>
                    <a href="javascript:void(0);" class="read-more" id="read-more-{{ $post->id }}" onclick="toggleText({{ $post->id }})" style="display:none;">
                        Ver más
                    </a>
                </p>
            </div>

            <!-- Mostrar los intereses asociados a la publicación -->
            @if($post->interests->isNotEmpty())
                <div class="margin-top-interes">
                    <strong>Intereses:</strong>
                    <div class="interests-container">
                        @foreach($post->interests as $interest)
                            <div class="interest-item">{{ $interest->name }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Mostrar la fecha de creación de la publicación en formato relativo -->
            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
            @if ($post->media_url)
                @php
                    $mediaExtension = pathinfo($post->media_url, PATHINFO_EXTENSION);
                @endphp

                @if (in_array($mediaExtension, ['mp4', 'avi']))
                    <!-- Video -->
                    <video controls class="media mt-2">
                        <source src="{{ asset('storage/' . $post->media_url) }}" type="video/{{ $mediaExtension }}">
                        Tu navegador no soporta el formato de video.
                    </video>
                @else
                    <!-- Imagen -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#mediaModal">
                        <img src="{{ asset('storage/' . $post->media_url) }}" alt="Media" class="media mt-2" onclick="openModal('{{ asset('storage/' . $post->media_url) }}')">
                    </a>
                @endif
            @endif

            <div class="card-footer">
    <!-- Botones de reacciones -->
    <div class="reaction-buttons">
        @csrf
        <button type="button" class="btn btn-sm btn-outline-primary react-btn" 
                data-reaction-type="like" data-post-id="{{ $post->id }}">
            👍 <span class="icon-reaction-number" id="like-count-{{ $post->id }}">{{ $post->getReactionCountByType('like') }}</span>
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger react-btn" 
                data-reaction-type="love" data-post-id="{{ $post->id }}">
            ❤️  <span class="icon-reaction-number" id="love-count-{{ $post->id }}">{{ $post->getReactionCountByType('love') }}</span>
        </button>
        <button type="button" class="btn btn-sm btn-outline-warning react-btn" 
                data-reaction-type="surprise" data-post-id="{{ $post->id }}">
            😲  <span class="icon-reaction-number" id="surprise-count-{{ $post->id }}">{{ $post->getReactionCountByType('surprise') }}</span>
        </button>
        <!-- Nuevas reacciones -->
        <button type="button" class="btn btn-sm btn-outline-success react-btn" 
                data-reaction-type="laugh" data-post-id="{{ $post->id }}">
            😂  <span class="icon-reaction-number" id="laugh-count-{{ $post->id }}">{{ $post->getReactionCountByType('laugh') }}</span>
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger react-btn" 
                data-reaction-type="angry" data-post-id="{{ $post->id }}">
            😡  <span class="icon-reaction-number" id="angry-count-{{ $post->id }}">{{ $post->getReactionCountByType('angry') }}</span>
        </button>
    </div>
</div>


            <form class="comment-form" data-post-id="{{ $post->id }}" onsubmit="return submitComment(event, {{ $post->id }})">
    @csrf
    <div class="comment-input-container">
        <!-- Imagen de perfil del usuario -->
        <div class="comment-user-avatar">
            @if(auth()->user()->profile && auth()->user()->profile->avatar)
                <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="Avatar" class="rounded-circle">
            @else
                <img src="{{ asset('storage/default-avatar.png') }}" alt="Avatar" class="rounded-circle">
            @endif
        </div>
        
        <!-- Área de texto para el comentario -->
        <textarea name="content" placeholder="Comentar como {{ auth()->user()->profile->nombre_perfil }}" required class="form-control comment-textarea"></textarea>
        
        <!-- Icono de flecha para enviar el comentario -->
        <button type="submit" class="comment-submit-btn">
        <i class="bi bi-arrow-right-circle"></i>
        </button>
    </div>
</form>
<div id="comments-container-{{ $post->id }}">
    @php
        $hasMore = $post->comments()->count() > 5;
    @endphp

    <x-comment-list 
        :comments="$post->comments()->latest()->paginate(5)" 
        :post="$post" 
        :hasMore="$hasMore" />
    
    @if($hasMore)
        <a href="#" class="load-more-comments" data-post-id="{{ $post->id }}" data-page="1">Cargar más comentarios</a>
    @endif
</div>
        </li>
    @endforeach
    
</ul>


<!-- Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <img id="modalImage" src="" class="img-fluid" alt="Imagen" />
      </div>
    </div>
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

    // Función para mostrar el botón "Ver más" solo si el contenido es largo
    document.addEventListener("DOMContentLoaded", function() {
        @foreach($posts as $post)
            const postContent = "{{ $post->content }}";
            const contentLength = postContent.length;

            if (contentLength > 250) {
                document.getElementById(`read-more-{{ $post->id }}`).style.display = 'inline';
            }
        @endforeach
    });
</script>


<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta publicación? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<style>

    /* Aumenta el tamaño de la imagen dentro del modal */
#modalImage {
  min-width: 90% !important;  
  min-height: 80vh !important   ; 
  object-fit: contain !important; 
}

.modal-header{
    background-color: #0d6efd !important;
}


    .media {
    display: block;          /* Hace que se comporte como un bloque */
    margin: 0 auto;          /* Centra horizontalmente */
    max-width: 100vw;          /* Limita el ancho al 80% del contenedor */
    max-height: 300px;       /* Limita la altura máxima */
}
    /* Estilos generales */
    .list-group-item {
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: #ffffff;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra suave */
    }

    .list-group-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Sombra más pronunciada al pasar el cursor */
    }

    /* Estilos para la imagen de perfil */
    .rounded-circle {
        border: 2px solid #dee2e6; /* Borde para la imagen de perfil */
    }

    /* Estilos para el nombre del usuario */
    .font-color {
        color: #333; /* Color oscuro para el nombre */
        font-weight: 600; /* Texto en negrita */
    }

    .font-color a {
        color: inherit; /* Hereda el color del texto */
        text-decoration: none; /* Sin subrayado */
    }

    .font-color a:hover {
        color: #007bff; /* Color azul al pasar el cursor */
    }

    /* Estilos para el contenido del post */
    .post-content {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
        margin-top: 10px;
    }

    .post-text {
        display: inline;
    }

    .more-text {
        display: none;
    }

    .read-more {
        color: #007bff;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        margin-left: 5px;
    }

    .read-more:hover {
        text-decoration: underline; /* Subrayado al pasar el cursor */
    }

    /* Estilos para los intereses */
    .interests-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .interest-item {
        background-color: #f1f1f1;
        border-radius: 15px;
        padding: 5px 10px;
        font-size: 14px;
        color: #333;
        border: 1px solid #ddd;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .interest-item:hover {
        background-color: #007bff; /* Color azul al pasar el cursor */
        color: #fff; /* Texto blanco al pasar el cursor */
    }

    /* Estilos para los botones de reacciones */
    .reaction-buttons {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .react-btn {
        background-color: transparent;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 14px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .react-btn:hover {
        background-color: #f8f9fa; /* Fondo gris claro al pasar el cursor */
        border-color: #007bff; /* Borde azul al pasar el cursor */
    }

    .icon-reaction-number {
        margin-left: 5px;
        color: #333;
    }

    /* Estilos para el área de comentarios */
    .comment-form {
        margin-top: 15px;
    }

    .comment-input-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comment-user-avatar img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #dee2e6;
    }

    .comment-textarea {
        flex: 1;
        border-radius: 20px;
        padding: 10px;
        border: 1px solid #dee2e6;
        resize: none; /* Evita que el textarea sea redimensionable */
    }

    .comment-textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .comment-submit-btn {
        background-color: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .comment-submit-btn i {
        font-size: 24px;
        color: #007bff;
        transition: color 0.3s ease;
    }

    .comment-submit-btn:hover i {
        color: #0056b3; /* Color azul más oscuro al pasar el cursor */
    }

    /* Estilos para los comentarios */
    .comment-text {
        font-size: 14px;
        color: #555;
        margin-top: 5px;
    }

    .load-more-comments {
        display: block;
        text-align: center;
        color: #007bff;
        margin-top: 10px;
        text-decoration: none;
    }

    .load-more-comments:hover {
        text-decoration: underline;
    }

    /* Estilos para el footer de la tarjeta */
    .card-footer {
        background-color: transparent;
        border-top: 1px solid #dee2e6;
        padding: 10px 0;
        margin-top: 15px;
    }
</style>

<script>
    function openModal(imageUrl) {
        var modalImage = document.getElementById("modalImage");
        modalImage.src = imageUrl;
    }
</script>
<script>

function submitComment(event, postId) {
    event.preventDefault();  // Prevenir la acción por defecto del formulario

    const content = document.querySelector(`#comment-form-${postId} textarea`).value;
    
    if (content.trim() === '') return;

    // Enviar el comentario mediante AJAX
    $.ajax({
        url: '/comments',  // Cambia esto según la ruta de tu API o controlador
        method: 'POST',
        data: {
            post_id: postId,
            content: content,
            _token: '{{ csrf_token() }}',  // Para la protección contra CSRF
        },
        success: function(response) {
            // Agregar el nuevo comentario a la lista sin reemplazarla completamente
            const commentsContainer = document.querySelector(`#comments-container-${postId}`);
            const newComment = `
                <div class="comment">
                    <p>${response.comment.content}</p>
                    <small>${response.comment.created_at}</small>
                </div>
            `;
            commentsContainer.insertAdjacentHTML('afterbegin', newComment);
            
            // Limpiar el área de texto después de enviar
            document.querySelector(`#comment-form-${postId} textarea`).value = '';

            // Actualizar el número de comentarios y verificar si "Ver más comentarios" debe mostrarse
            updateLoadMoreButton(postId, response.commentsCount);
        }
    });
}

function updateLoadMoreButton(postId, commentsCount) {
    const loadMoreButton = document.querySelector(`#load-more-comments-${postId}`);
    if (commentsCount > 5) {
        loadMoreButton.style.display = 'inline-block';  // Mostrar el botón
    } else {
        loadMoreButton.style.display = 'none';  // Ocultar el botón si no hay más comentarios
    }
}

function setDeletePost(postId) {
    const formId = `delete-form-${postId}`;
    const form = document.getElementById(formId);
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    confirmDeleteButton.onclick = function() {
        form.submit();
    };
}
//Cargar reacciones 
$(document).on('click', '.react-btn', function () {
    var button = $(this);
    var postId = button.data('post-id');
    var reactionType = button.data('reaction-type');
    var _token = $('input[name="_token"]').val();

    console.time('ajaxRequestTime');  // Inicia la medición de tiempo

    $.ajax({
        url: '/posts/' + postId + '/reactions',
        method: 'POST',
        data: {
            _token: _token,
            reaction_type: reactionType,
        },
        success: function (response) {
            // Actualizamos los contadores para todas las reacciones
            $('#like-count-' + postId).text(response.likeCount);
            $('#love-count-' + postId).text(response.loveCount);
            $('#surprise-count-' + postId).text(response.surpriseCount);
            $('#laugh-count-' + postId).text(response.laughCount);  // Nuevo contador para 'laugh'
            $('#angry-count-' + postId).text(response.angryCount);  // Nuevo contador para 'angry'
            
            console.log('Reacción registrada con éxito:', response);
            console.timeEnd('ajaxRequestTime');  // Mide el tiempo de la solicitud
        },
        error: function (xhr, status, error) {
            console.log('Error al registrar la reacción:', error);
            console.timeEnd('ajaxRequestTime');  // Mide el tiempo de la solicitud incluso en caso de error
        }
    });
});


// Enviar un comentario
$(document).on('submit', '.comment-form', function(e) {
    e.preventDefault(); // Prevenir el envío del formulario por defecto
    var postId = $(this).data('post-id'); // Obtener el ID de la publicación
    var content = $(this).find('textarea[name="content"]').val(); // Obtener el contenido del comentario
    var _token = $('input[name="_token"]').val(); // Obtener el token CSRF

    $.ajax({
        url: '/posts/' + postId + '/comments',
        method: 'POST',
        data: {
            _token: _token,
            content: content
        },
        success: function(response) {
            $('#comments-container-' + postId).html(response.comments); 
            $('.comment-form textarea').val('');
        },
        error: function(xhr, status, error) {
            console.log('Error:', error);
        }
    });
});

//Cargar mas comentarios
$(document).on('click', '.load-more-comments', function() {
    var button = $(this);
    var postId = button.data('post-id');
    var page = button.data('page'); // Página actual
    var container = $('#comments-container-' + postId);

    // Desactivar el botón mientras se carga
    if (button.prop('disabled')) return; // Prevenir múltiples solicitudes
    button.prop('disabled', true);
    button.text('Cargando...');

    // Realizar la solicitud AJAX para cargar más comentarios
    $.ajax({
        url: '/posts/' + postId + '/comments/load-more',
        method: 'GET',
        data: { page: page },
        success: function(response) {
            // Eliminar cualquier botón duplicado en el contenedor


            // Agregar los nuevos comentarios al contenedor
            container.append(response.comments);
            container.find('.load-more-comments').remove();
            // Si no hay más comentarios, ocultar el botón
            if (!response.hasMore) {
                button.remove(); // Eliminar el botón si no hay más comentarios
            } else {
                // Actualizar la página para la siguiente carga
                button.data('page', page + 1);
                button.prop('disabled', false);
                button.text('Ver más comentarios');

                // Mover el botón al final del contenedor
                container.append(button);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error:', error);
            button.prop('disabled', false);
            button.text('Ver más comentarios');
        }
    });
});



</script>
