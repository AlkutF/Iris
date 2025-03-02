@extends('layouts.app')
@include('components.navbar')

@section('content')
@php
    $fondos = [
        'Desarrollo de Software' => 'storage/assets/Fondos/Software.webp',
        'Diseño Gráfico' => 'storage/assets/Fondos/Diseño.webp',
        'Entrenamiento Deportivo' => 'storage/assets/Fondos/Entrenamiento.webp',
        'Educación Inicial' => 'storage/assets/Fondos/Educacion.webp',
        'Mecánica Automotriz' => 'storage/assets/Fondos/Mecanica.webp',
        'Educación Básica' => 'storage/assets/Fondos/Educacion_Basica.webp',
        'Electrónica' => 'storage/assets/Fondos/Electronica.webp',
        'Gastronomía' => 'storage/assets/Fondos/Gastronomia.webp',
        'Redes & Telecomunicaciones' => 'storage/assets/Fondos/Telecomunicacioines.webp',
        'Contabilidad y Asesoría Tributaria' => 'storage/assets/Fondos/Contabilidad.webp',
        'Educación Inclusiva' => 'storage/assets/Fondos/Educacion_Inclusiva.webp',
        'Marketing & Comercio Electrónico' => 'storage/assets/Fondos/Marketing_Electrónico.webp',
        'Talento Humano' => 'storage/assets/Fondos/Talento_Humano.webp',
    ];
    $fondo = $fondos[$profile->carrera] ?? 'storage/assets/Fondo.webp';
@endphp
<!-- Contenedor con imagen de fondo -->
<div class="container-fluid position-relative" 
    style="width: 100%; height: 150px; background-image: url('{{ asset($fondo) }}'); 
           background-size: cover; background-position: center center; 
           background-repeat: no-repeat; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
    
<!-- Avatar (en la parte inferior izquierda) -->
<div class="position-absolute bottom-0 start-0">
    <img 
        src="{{ asset('storage/' . $profile->avatar) }}" 
        alt="Avatar" 
        class="rounded-circle shadow-lg" 
        style="width: 110px; height: 110px; object-fit: cover; border: 5px solid white; margin-left: 20px; margin-bottom: 20px;"
        data-bs-toggle="modal" 
        data-bs-target="#mediaModal"
        id="avatarImage"
    >
</div>
</div>


    <div class="container mt-5">
        <div class="row">
            <!-- Sección 1: Perfil -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-3">
                    <div class="backgraund-element">
                    <span>{{ $profile->nombre_perfil }}</span>
                    </div>
                    <div class="card-body text-center">
    <img 
        src="{{ asset('storage/' . $profile->avatar) }}" 
        alt="Avatar" 
        class="rounded-circle mb-3" 
        style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f8f9fa; cursor: pointer;"
        data-bs-toggle="modal" 
        data-bs-target="#mediaModal"
    >

    @if(auth()->check() && auth()->user()->id == $profile->user_id)
        <a href="{{ route('profile.edit', $profile->user_id) }}" class="btn-event">
            <i class="fas fa-edit"></i> Editar perfil
        </a>
    @endif

    <h6 class="mb-2">Un poco sobre mí:</h6>
    <p>{{ $profile->bio }}</p>
    <h6 class="mb-2">Estudio:</h6>
    <p>{{ $profile->carrera ?? 'No registrado' }}</p>

    <h6 class="mb-2">Me intereso en:</h6>
    <ul class="list-unstyled">
        @foreach ($profile->interests as $interest)
            <li class="badge bg-light text-dark mb-1 interes-item">{{ $interest->name }}</li>
        @endforeach
    </ul>

    @if($profile->gender)
        <h6 class="mb-2">Género:</h6>
        <p>
            @if($profile->gender == 'male')
                Masculino
            @elseif($profile->gender == 'female')
                Femenino
            @else
                Sin especificar
            @endif
        </p>
    @endif
</div>

                    <div class="card-footer bg-transparent border-0">
    @if(auth()->user()->id == $profile->user_id)
   
    @elseif($friendship)
        <!-- Si ya son amigos -->
        <form action="{{ route('friendships.destroy', $friendship->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-block mb-2">Terminar amistad</button>
        </form>
    @elseif($friendRequest && $friendRequest->sender_id == auth()->user()->id)
        <!-- Si el usuario ha enviado la solicitud -->
        <form action="{{ route('friend_requests.destroy', $friendRequest->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-block mb-2">Cancelar solicitud</button>
        </form>
    @elseif($friendRequest && $friendRequest->receiver_id == auth()->user()->id)
        <!-- Si el usuario ha recibido una solicitud -->
        <form action="{{ route('friend_requests.accept', $profile->user_id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success btn-block mb-2">Aceptar solicitud</button>
        </form>
        <form action="{{ route('friend_requests.reject', $profile->user_id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-block mb-2">Rechazar solicitud</button>
        </form>
    @else
        <!-- Si no hay ninguna relación, enviar solicitud de amistad -->
        <form action="{{ route('friend_requests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $profile->user_id }}">
            <button type="submit" class="btn btn-success btn-block mb-2">Enviar solicitud de amistad</button>
        </form>
    @endif
</div>
                </div>
            </div>

            <!-- Sección 2: Publicaciones del usuario -->
            <div class="col-md-8 mb-4">
                <div class="card border-0 rounded-3">
                    <div class="backgraund-element">
                        <span>Publicaciones de {{ $profile->nombre_perfil }}</span>
                    </div>
                    <div class="card-body">
                        <div class="post-list-container">
                            <x-post-list :posts="$posts" />
                            @if($posts->hasMorePages())
                                <button id="loadMoreBtn" class="btn btn-outline-primary width-btn-100">Cargar más posteos</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" class="img-fluid" alt="Avatar en el modal">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalLabel">Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalAvatar" src="" alt="Avatar" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

@endsection
<style>
    /* Estilos generales */
    body {
        background-color: #f8f9fa; /* Fondo gris claro */
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .backgraund-element {
        background-color: #a2d5f2; /* Azul pastel */
        padding: 10px;
        border-radius: 10px 10px 0 0;
        color: white;
        font-weight: bold;
    }

    .btn-event {
        background-color: #a2d5f2; /* Azul pastel */
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .btn-event:hover {
        background-color: #89c3e0; /* Azul pastel más oscuro */
    }

    .badge {
        padding: 5px 10px;
        margin-right: 5px;
        background-color: #f8c1d9; /* Rosa pastel */
        color: #333;
    }

    .rounded-circle {
        border: 4px solid #f8f9fa;
    }

    .card-body {
        padding: 20px;
        background-color: white;
    }

    .card-footer {
        padding: 15px;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }

    .post-list-container {
        max-height: 500px;
        overflow-y: auto;
        margin-bottom: 20px;
    }

    .width-btn-100 {
        width: 100%;
    }
    .interes-item{
        background-color: #70D6FF; !important;
    }
    /* Botones de reacciones */
    .react-btn {
        background-color: transparent;
        border: 1px solid #a2d5f2; /* Azul pastel */
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 14px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .react-btn:hover {
        background-color: #a2d5f2; /* Azul pastel */
        border-color: #89c3e0; /* Azul pastel más oscuro */
    }

    /* Área de comentarios */
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
        border: 1px solid #e9ecef;
    }

    .comment-textarea {
        flex: 1;
        border-radius: 20px;
        padding: 10px;
        border: 1px solid #e9ecef;
        resize: none;
    }

    .comment-textarea:focus {
        border-color: #a2d5f2; /* Azul pastel */
        box-shadow: 0 0 0 2px rgba(162, 213, 242, 0.25);
    }

    .comment-submit-btn {
        background-color: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .comment-submit-btn i {
        font-size: 24px;
        color: #a2d5f2; /* Azul pastel */
        transition: color 0.3s ease;
    }

    .comment-submit-btn:hover i {
        color: #89c3e0; /* Azul pastel más oscuro */
    }

    /* Botones de amistad */
    .btn-success {
        background-color: #b2f2bb; /* Verde pastel */
        border: none;
        color: #333;
    }

    .btn-success:hover {
        background-color: #9de0a6; /* Verde pastel más oscuro */
    }

    .btn-danger {
        background-color: #f8c1d9; /* Rosa pastel */
        border: none;
        color: #333;
    }

    .btn-danger:hover {
        background-color: #e6b0c7; /* Rosa pastel más oscuro */
    }
        /* Aumenta el tamaño de la imagen dentro del modal */
#modalImage {
  min-width: 50% !important;  
  min-height: 30vh !important   ; 
  object-fit: contain !important; 
}
</style>
@section('scripts')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Selecciona todas las imágenes que deben abrir el modal
        const avatarImages = document.querySelectorAll("img[data-bs-target='#mediaModal']");
        const modalImage = document.getElementById("modalImage");

        // Agrega un evento a cada imagen
        avatarImages.forEach(img => {
            img.addEventListener("click", function () {
                modalImage.src = this.src; // Asigna la imagen clickeada al modal
            });
        });
    });
</script>

<script>


function loadMoreStories() {
    fetch('/ruta-para-cargar-mas-historias')  
        .then(response => response.json())
        .then(data => {
            const container = document.querySelector('.story-container');
            if (data.stories) {
                container.innerHTML += data.stories;
            }
            if (!data.has_more) {
                console.log('No hay más historias para cargar.');
            }
        });
}

$(document).ready(function() {
        let currentPage = 1;
        
        // Manejo del clic en el botón "Cargar más"
        $('#loadMoreBtn').click(function(e) {
            e.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
            currentPage++; // Incrementar la página

            // Realizar la solicitud AJAX
            $.ajax({
                url: "{{ url()->current() }}?page=" + currentPage, // Usar la URL actual con el parámetro de página
                type: 'GET',
                success: function(response) {
                    // Añadir los nuevos posts al listado
                    $('#posts-list').append(response.posts);

                    // Si no hay más publicaciones, ocultar el botón
                    if (!response.has_more) {
                        $('#loadMoreBtn').hide();
                    }
                },
                error: function() {
                    alert('Hubo un error al cargar los posts.');
                }
            });
        });
    });

// Reaccionar a una publicación
$(document).on('click', '.react-btn', function () {
    var button = $(this);
    var postId = button.data('post-id');
    var reactionType = button.data('reaction-type');
    var _token = $('input[name="_token"]').val();

    $.ajax({
        url: '/posts/' + postId + '/reactions',
        method: 'POST',
        data: {
            _token: _token,
            reaction_type: reactionType,
        },
  
        success: function (response) {
            // Actualizar los contadores
            $('#like-count-' + postId).text(response.likeCount);
            $('#love-count-' + postId).text(response.loveCount);
            $('#surprise-count-' + postId).text(response.surpriseCount);
            console.log('Reacción registrada con éxito:', response);
        },
        error: function (xhr, status, error) {
            console.log(url);
          // Actualizar los contadores
          console.log(response);
            console.log('Error al registrar la reacción:', error);
        },
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
    // Añadir el nuevo comentario al principio o al final del contenedor de comentarios
    $('#comments-container-' + postId).prepend(response.comment); // O append() si prefieres agregarlo al final
    
    // Limpiar el campo de texto del comentario
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


// Cuando el usuario haga clic en el botón de editar
$(document).on('click', '.edit-comment-btn', function() {
    var commentId = $(this).data('comment-id');
    $.ajax({
        url: '/comments/' + commentId + '/edit',
        method: 'GET',
        success: function(response) {
            if (response.comment) {
                // Llenar los campos del formulario con los datos del comentario
                $('#editCommentModal').find('textarea[name="content"]').val(response.comment.content);
                $('#editCommentModal').find('form').attr('action', '/comments/' + commentId); // Actualizar la URL del formulario de edición
                $('#editCommentModal').modal('show'); // Mostrar el modal para editar el comentario
            }
        },
        error: function(xhr, status, error) {
            alert('Error al obtener el comentario para editar.');
        }
    });
});

// Para actualizar el comentario después de editar
$(document).on('submit', '#editCommentForm', function(e) {
    e.preventDefault();

    var formData = $(this).serialize(); // Obtener los datos del formulario

    $.ajax({
        url: $(this).attr('action'), // URL del formulario
        method: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                // Actualizar el contenido del comentario en el DOM sin recargar la página
                $('#comment-' + response.comment.id).find('.comment-content').text(response.comment.content);

                // Cerrar el modal o el formulario de edición
                $('#editCommentModal').modal('hide');
            }
        },
        error: function(xhr, status, error) {
            alert('Error al actualizar el comentario.');
        }
    });
});


    // Eliminar comentario
    $(document).on('click', '.delete-comment-btn', function() {
    var commentId = $(this).data('comment-id');
    
    if (confirm('¿Estás seguro de que quieres eliminar este comentario?')) {
        $.ajax({
            url: '/comments/' + commentId,  // URL de la eliminación
            method: 'DELETE',
            data: {
                _token: $('input[name="_token"]').val()  // Asegúrate de incluir el token CSRF
            },
            success: function(response) {
                $('#comment-' + commentId).remove();
            },
            error: function(xhr, status, error) {
                alert('Error al eliminar el comentario.');
                console.log('Error:', error);
            }
        });
    }
});


    //Recargar las notificaciones
function updateNotifications() {
    // Realizar la solicitud AJAX para obtener las notificaciones sin leer
    fetch("{{ route('notifications.unread') }}")
        .then(response => response.json())
        .then(data => {
            // Actualizar el contenido de las notificaciones
            const notificationsList = document.querySelector('.notifications-list');
            notificationsList.innerHTML = ''; // Limpiar las notificaciones existentes

            if (data.length === 0) {
                notificationsList.innerHTML = '<li class="list-group-item">No tienes notificaciones sin leer.</li>';
            } else {
                // Reemplazar con las nuevas notificaciones
                data.forEach(group => {
                    group.forEach(notification => {
                        const postId = notification.data.post_id;
                        const reactedByCount = notification.data.reacted_by.length;
                        const notificationText = `${reactedByCount} ${reactedByCount > 1 ? 'personas han reaccionado' : 'persona ha reaccionado'} a tu publicación.`;
                        
                        const notificationItem = document.createElement('li');
                        notificationItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

                        const link = document.createElement('a');
                        link.href = `/notificaciones/redirect/${postId}/${notification.id}`;
                        link.textContent = notificationText;

                        notificationItem.appendChild(link);
                        notificationsList.appendChild(notificationItem);
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar las notificaciones:', error);
        });
}  

//Marcar algo como leido 
$(document).on('submit', '.mark-as-read-form', function(event) {
    event.preventDefault(); // Evitar la acción por defecto del formulario

var form = $(this);
var notificationId = form.find('button').data('notification-id'); // Obtener el ID de la notificación
var url = form.attr('action'); // Obtener la URL del formulario

// Realizar la solicitud AJAX
$.ajax({
    url: url,
    method: 'PATCH',
    data: form.serialize(), // Serializar los datos del formulario
    success: function(response) {
        // Eliminar la notificación del DOM
        $('#notification-' + notificationId).remove();
    },
    error: function(xhr, status, error) {
        console.error('Error al marcar como leída:', error);
    }
});
});


</script>
@endsection
