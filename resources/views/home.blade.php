@extends('layouts.app')
@include('components.navbar')

@section('content')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <div class="container mt-3">
        <div class="row">
<div class="col-md-3 mb-4">
    <div class="card">
        <div class="card-body text-center">
            @if(auth()->user()->profile)
                <!-- Avatar del usuario -->
                <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" 
                     alt="Avatar" 
                     class="avatar-icon mb-3">
                
                <!-- Nombre del perfil -->
                <h5 class="card-title">
                    {{ auth()->user()->profile->nombre_perfil ?? auth()->user()->name }}
                </h5>

                <!-- Carrera del usuario -->
                <p class="card-text text-muted mb-2">
                    <strong>Carrera:</strong> {{ auth()->user()->profile->carrera ?? 'No especificado' }}
                </p>

                <!-- Biografía del usuario -->
                @if(auth()->user()->profile->bio)
                    <p class="card-text text-muted mb-3">
                        {{ auth()->user()->profile->bio }}
                    </p>
                @else
                    <p class="card-text text-muted mb-3">
                        No hay biografía disponible.
                    </p>
                @endif

                <!-- Intereses del usuario -->
                @if(auth()->user()->profile->interests->count() > 0)
                    <div class="interests-container mb-3">
                        <strong>Intereses:</strong>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                        @foreach(auth()->user()->profile->interests->take(5) as $interest)
                                <span class="font-size-8">{{ $interest->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p>No tienes intereses registrados.</p>
                @endif

                <!-- Botón solo visible para administradores -->
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger mt-3">Ir al panel de administración</a>
                @endif

            @else
                <p>No tienes perfil aún.</p>
            @endif
        </div>
    </div>
</div>


            <!-- Columna 2: Publicaciones (3/6 de la pantalla) -->
            <div class="col-md-6 mb-4">
                <div class="card mb-3 oculto">
                    <div class="card-body oculto">
                            <!-- Iterar sobre las historias -->
                            <div class="story-container" id="story-container">
                                @foreach($stories as $story)
                                    <x-story :story="$story" />
                                @endforeach
                                
                            </div>
                            <!-- Agregar más historias según sea necesario -->
                 </div>
                </div>

                <div class="card">
    <div class="card-body">
        <!-- Formulario minimalista -->
        <div class="post-form mb-4">
            <form>
                <div class="mb-3">
                    <!-- Textarea -->
                    <textarea class="form-control" rows="3" placeholder="¿Qué estás pensando?" style="resize: none;" id="openModalTextarea"></textarea>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#postModal">
                        <i class="bi bi-image"></i> Foto
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#postModal">
                        <i class="bi bi-camera-video"></i> Video
                    </button>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#postModal">Publicar</button>
                </div>
            </form>
        </div>

        <!-- Lista de posts -->
        <div class="post-list-container">
            <x-post-list :posts="$posts" />
            @if($posts->hasMorePages())
                <button id="loadMoreBtn" class="btn-event width-btn-100">Cargar más posteos</button>
            @endif
        </div>
    </div>
</div>
            </div>

            <!-- Columna 3: Notificaciones (1/6 de la pantalla) -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group notifications-list">
                            <x-notifications-component :unreadNotifications="$unreadNotifications" /> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- Modal -->
<div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Crear Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Contenido de la publicación -->
                    <div class="mb-3">
                        <label for="content" class="form-label">¿Qué estás pensando?</label>
                        <textarea name="content" id="content" class="form-control" rows="4" placeholder="Escribe tu publicación..." required></textarea>
                    </div>

                    <!-- Multimedia -->
                    <div class="mb-3">
                        <label for="media" class="form-label">Subir Imagen/Video</label>
                        <input type="file" name="media" id="media" class="form-control" accept="image/*,video/*">
                    </div>

                    <!-- Vista previa de la imagen o video -->
                    <div id="mediaPreview" class="mb-3"></div>

                    <!-- Búsqueda de intereses -->
                    <div class="mb-3">
                        <label for="interestSearch" class="form-label">Buscar intereses</label>
                        <input type="text" id="interestSearch" class="form-control" placeholder="Buscar intereses...">
                    </div>

                    <!-- Lista de intereses -->
                    <div class="mb-3">
                        <label class="form-label">Selecciona tus intereses</label>
                        <div id="interestList" class="form-check" style="max-height: 50px; overflow-y: auto; display: flex; flex-wrap: wrap;">
                            @foreach ($interests as $interest)
                                <div class="form-check me-3 mb-2">
                                    <input 
                                        type="checkbox" 
                                        name="interests[]" 
                                        value="{{ $interest->id }}" 
                                        id="interest_{{ $interest->id }}" 
                                        class="form-check-input">
                                    <label class="form-check-label" for="interest_{{ $interest->id }}">
                                        {{ $interest->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botón para publicar -->
                    <button type="submit" class="btn btn-primary btn-sm w-100">Publicar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>

.container{
    min-width: 96vw;
}
.font-size-8{
    font-size: 0.8rem;
    background: linear-gradient(90deg, rgb(66, 135, 209) 0%, rgb(66, 135, 209) 100%);
    color: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    padding: 5px 10px;
    border-radius: 15px;
}
    /* Estilos generales del modal */
.modal-content {
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
    background-color: #f8f9fa; /* Fondo gris claro */
}

/* Estilo del encabezado */
.modal-header {
    background-color:#5d71f1; /* Azul oscuro elegante */
    color: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

/* Botón de cerrar */
.btn-close {
    filter: invert(1);
}

/* Estilo del textarea */
textarea.form-control {
    border-radius: 8px;
    border: 1px solid #ccc;
    background-color: white;
}

/* Botón de publicar */
.btn-primary {
    background-color: #5d71f1;
    border: none;
    border-radius: 8px;
    padding: 10px;
    font-size: 16px;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background-color: #5d71f1;
}

/* Estilos de los botones de intereses */
.form-check-input:checked {
    background-color: #5d71f1;
    border-color: #5d71f1;
}

/* Lista de intereses */
#interestList {
    max-height: 50px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 8px;
}

/* Estilo para la vista previa de imágenes/videos */
#mediaPreview {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px dashed #ccc;
    padding: 10px;
    min-height: 120px;
    background-color: white;
    border-radius: 8px;
}

#mediaPreview img,
#mediaPreview video {
    max-width: 100%;
    max-height: 250px;
    border-radius: 8px;
}

        /* Estilos para el avatar */
        .avatar-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover; /* Asegura que la imagen cubra el espacio */
    }

    /* Estilos para el título (nombre del usuario) */
    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 10px;
        color: #333;
    }

    /* Estilos para la biografía */
    .card-text {
        font-size: 0.9rem;
        line-height: 1.5;
        color: #666;
    }

    .card-body{
        margin-left :-10px !important;
    }

    /* Estilos para los intereses */
    .interests-container {
        margin-top: 10px;
    }

    .interests-container strong {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    .badge.bg-primary {
 
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9rem;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .avatar-icon {
            width: 80px;
            height: 80px;
        }

        .card-title {
            font-size: 1.3rem;
        }

        .card-text {
            font-size: 0.8rem;
        }

        .badge.bg-primary {
            font-size: 0.8rem;
        }
    }
    
/**ZOna inutil borrar despues/ */
.post-form h5 {
    font-size: 1.25rem;
    font-weight: 500;
    color: #333;
}

.post-form textarea {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    font-size: 0.9rem;
}



.post-form .btn-outline-secondary {
    border-color: #ddd;
    color: #555;
}

.post-form .btn-outline-secondary:hover {
    background-color: #f8f9fa;
    border-color: #ccc;
}

.post-form .btn-primary {
    background-color: #007bff;
    border: none;
    padding: 5px 15px;
    font-size: 0.9rem;
}

.post-form .btn-primary:hover {
    background-color: #0056b3;
}
#interestList {
        max-height: 100px;
        overflow-y: auto;
        display: flex;
        flex-wrap: wrap;
    }
    .modal-content {
        border-radius: 15px;
    }

@media screen and (max-width: 768px) {
    .post-form .btn-primary {
        width: 100%;
    }
    .post-form .btn-outline-secondary {
        display: none;
    }
}
    

</style>
@endsection

@section('scripts')

<script>
  document.getElementById("openModalTextarea").addEventListener("focus", function () {
    var postModal = new bootstrap.Modal(document.getElementById("postModal"));
    postModal.show();
  });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Filtrado de intereses
        document.getElementById('interestSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const allInterests = document.querySelectorAll('#interestList .form-check');
            
            allInterests.forEach(function(interestDiv) {
                const label = interestDiv.querySelector('.form-check-label');
                let text = label.textContent.trim().toLowerCase();
                text = text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                
                if (text.includes(query)) {
                    interestDiv.style.display = 'block';
                } else {
                    interestDiv.style.display = 'none';
                }
            });
        });

        // Vista previa de la imagen o video
        document.getElementById('media').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const mediaPreview = document.getElementById('mediaPreview');
            mediaPreview.innerHTML = '';

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileType = file.type.split('/')[0];
                    let previewContent = '';

                    if (fileType === 'image') {
                        previewContent = `<img src="${e.target.result}" alt="Vista previa" class="img-fluid" style="max-height: 300px;">`;
                    } else if (fileType === 'video') {
                        previewContent = `<video controls class="img-fluid" style="max-height: 300px;"><source src="${e.target.result}" type="${file.type}"></video>`;
                    }

                    mediaPreview.innerHTML = previewContent;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('story-container').addEventListener('scroll', function() {
    const container = this;
    if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 10) {
        loadMoreStories();
    }
});

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

