@extends('layouts.app')
@include('components.navbar')

@section('content')
    <div class="container mt-4">
        <div class="row">
<!-- Columna 1: Datos del Grupo (4/12 de la pantalla) -->
<div class="col-md-4 mb-4">
    <div class="card">
        <div class="card-body">
            <h5>{{ $group->name }}</h5>
            @if($group->image_url)
           	<img src="{{asset($group->image_url)}}" alt="Imagen del grupò" class="img-fluid mb-2">
            @endif
            <p><strong>Descripción:</strong> {{ $group->description }}</p>
            <p><strong>Tipo:</strong> {{ ucfirst($group->type) == 'Public' ? 'Público' : 'Privado' }}</p>
            @if($group->creator)
                <p><strong>Creador:</strong> {{ $group->creator->name }}</p>
            @endif

            <!-- Mostrar intereses del grupo -->
            @if($group->interests->isNotEmpty())
                <p><strong>Intereses:</strong></p>
                <ul>
                    @foreach($group->interests as $interest)
                        <li>{{ $interest->name }}</li>
                    @endforeach
                </ul>
            @endif

<div class="container">
    
    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3 text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

     <!-- Botones exclusivos del creador del grupo -->
     @if(auth()->user()->id == optional($group->creator)->id)
        <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap mb-3">
            <!-- Editar grupo -->
            <form action="{{ route('groups.edit', $group->id) }}" method="GET">
                <button type="submit" class="btn btn-warning">
                    ✏️ Editar Grupo
                </button>
            </form>
            <!-- Eliminar grupo -->
            <form action="{{ route('groups.destroyGroup', $group->id) }}" method="POST" id="delete-form-{{ $group->id }}">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="setDeleteGroup({{ $group->id }})">
                    🗑️ Eliminar Grupo
                </button>
            </form>
        </div>
    @endif


    <!-- Botones generales -->
    <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap mb-3">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#createPostModalSolicitud">
            📢 Solicitar Publicación
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#createRegaloModal">
            ❤️ Enviar Regalo de San Valentín
        </button>
    </div>

            <!-- Botón exclusivo para administradores -->
                @if($isAdmin)
                    <div class="text-center">
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#createPostModal">
                            ✨ Crear Nueva Publicación
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>


            <!-- Columna 2: Publicaciones del Grupo (6/12 de la pantalla) -->
            @php
                $userGroupMember = $group->members()->where('user_id', auth()->user()->id)->first();
                $memberStatus = $userGroupMember ? $userGroupMember->pivot->status : 'pending';
            @endphp
            @if($group->type == 'public' || $memberStatus == 'accepted')
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                        @foreach($filteredPosts->sortByDesc('created_at') as $post)
                            <x-group-post :post="$post" :group="$group" />
                        @endforeach
                        </div>
                    </div>
                </div>
            @endif

            
        </div>
    </div>
@endsection

<!-- Modal para crear un Regalo de San Valentín -->
<div class="modal fade" id="createRegaloModal" tabindex="-1" aria-labelledby="createRegaloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content valentines-modal">
            <div class="modal-header text-center">
                <h2 class="modal-title" id="createRegaloModalLabel">
                    💕Envíale un Regalo Especial
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Imagen decorativa de San Valentín -->
                <div class="text-center mb-3">
                    <img src="https://cdn.pixabay.com/animation/2022/08/23/03/32/03-32-04-108_512.gif" alt="Corazones flotantes" class="valentine-gif">
                </div>
                <!-- Formulario para crear el regalo -->
                <form action="{{ route('regalos.store') }}" method="POST">
                    @csrf
                    <!-- Nombre de la Pareja -->
                    <div class="mb-3">
                        <label for="nombre_pareja" class="form-label">💘 Nombre de la Pareja</label>
                        <input type="text" class="form-control input-valentine" name="nombre_pareja" required placeholder="Escribe su nombre aquí">
                    </div>
                    <!-- Carrera -->
                    <div class="mb-3">
                        <label for="carrera" class="form-label">📚 Carrera</label>
                        <select class="form-control input-valentine" name="carrera" required>
                            <option value="Desarrollo de Software">Desarrollo de Software</option>
                            <option value="Diseño Gráfico">Diseño Gráfico</option>
                            <option value="Entrenamiento Deportivo">Entrenamiento Deportivo</option>
                            <option value="Educación Inicial">Educación Inicial</option>
                            <option value="Mecánica Automotriz">Mecánica Automotriz</option>
                            <option value="Educación Básica">Educación Básica</option>
                            <option value="Electrónica">Electrónica</option>
                            <option value="Gastronomía">Gastronomía</option>
                            <option value="Redes & Telecomunicaciones">Redes & Telecomunicaciones</option>
                            <option value="Contabilidad y Asesoría Tributaria">Contabilidad y Asesoría Tributaria</option>
                            <option value="Educación Inclusiva">Educación Inclusiva</option>
                            <option value="Marketing & Comercio Electrónico">Marketing & Comercio Electrónico</option>
                            <option value="Talento Humano">Talento Humano</option>
                            <option value="Administrativo">Administrativo</option>
                        </select>
                    </div>
                    <!-- Semestre -->
                    <div class="mb-3">
                        <label for="semestre" class="form-label">🎓 Semestre</label>
                        <select class="form-control input-valentine" name="semestre" required>
                            <option value="Primero">Primero</option>
                            <option value="Segundo">Segundo</option>
                            <option value="Tercero">Tercero</option>
                            <option value="Cuarto">Cuarto</option>
                            <option value="Quinto">Quinto</option>
                            <option value="Administrativo">Administrativo</option>
                        </select>
                    </div>
                    <!-- Opción de Anonimato -->
                    <div class="mb-3">
                        <label for="anonimato" class="form-label">💌 ¿Deseas que sea anónimo?</label>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="form-check form-check-inline">
                                <input type="radio" id="anonimato_si" name="anonimato" value="1" class="form-check-input" checked>
                                <label for="anonimato_si" class="form-check-label">Sí 💕</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="anonimato_no" name="anonimato" value="0" class="form-check-input">
                                <label for="anonimato_no" class="form-check-label">No 😊</label>
                            </div>
                        </div>
                    </div>
                    <!-- Botón para enviar -->
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-valentine">💝 Enviar Regalo</button>
                    </div>
                    <!-- Campo oculto para el ID del grupo -->
                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Creación de Publicación -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content create-post-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Crear Nueva Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('group.posts.store', $group->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Contenido -->
                    <div class="mb-3">
                        <textarea class="form-control" name="content" rows="3" placeholder="¿Qué estás pensando?" required></textarea>
                    </div>

                    <!-- Carga de multimedia -->
                    <div class="mb-3">
                        <label for="media_url" class="form-label">Añadir Multimedia (opcional)</label>
                        <div class="input-group-file">
                            <input type="file" class="form-control" name="media_url" id="media_url">
                            <label for="media_url">Seleccionar Archivo</label>
                            <span>O arrastra tu archivo aquí</span>
                        </div>
                    </div>

                    <!-- Botón de publicación -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Publicar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Solicitar Publicación -->
<div class="modal fade" id="createPostModalSolicitud" tabindex="-1" aria-labelledby="createPostModalSolicitudLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content group-post-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalSolicitudLabel">
                    📢 ¡Solicita tu Publicación! ✨
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Imagen decorativa -->
                <div class="text-center mb-3">
                    <img src="https://media1.giphy.com/media/WoWm8YzFQJg5i/giphy.gif?cid=6c09b952f7ps03ze5ivwsgtxh8qpv5suqs8g0s2o4i0f9it4&ep=v1_gifs_search&rid=giphy.gif&ct=g" alt="Publicación Animada" class="group-post-gif">
                </div>
                <!-- Formulario para solicitar publicación -->
                <form action="{{ route('groups.requestPost', $group->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Contenido -->
                    <div class="mb-3">
                        <label for="content" class="form-label">📝 Contenido de la Publicación</label>
                        <textarea id="content" name="content" class="form-control input-group-post" required maxlength="1000" rows="3" placeholder="Escribe tu mensaje aquí..."></textarea>
                    </div>
                    <!-- Archivo multimedia -->
                    <div class="mb-3">
                        <label for="media_url" class="form-label">📸 Archivo Multimedia (Opcional)</label>
                        <div class="input-group-file">
                            <input type="file" name="media_url" id="media_url" accept="image/*,video/*" />
                            <label for="media_url">Seleccionar Archivo</label>
                            <span>O arrastra tu archivo aquí</span>
                        </div>
                    </div>
                    <!-- Botón para enviar -->
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-group-post">🚀 Enviar Solicitud</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function setDeleteGroup(postId) {
        const formId = `delete-form-${postId}`;
        const form = document.getElementById(formId);
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        confirmDeleteButton.onclick = function() {
            form.submit();
        };
    }
</script>


<style>
/* Estilos del Modal de San Valentín */
.valentines-modal {
    background: linear-gradient(to bottom, #ff9a9e, #fad0c4);
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(255, 0, 102, 0.3);
    padding: 20px;
}

.modal-title {
    font-family: 'Dancing Script', cursive;
    font-size: 28px;
    font-weight: bold;
    color: #fff;
    text-shadow: 2px 2px 8px rgba(255, 255, 255, 0.6);
}

/* Estilo de inputs */
.input-valentine {
    border: 2px solid #ff6b81;
    border-radius: 10px;
    padding: 10px;
    background: #ffecec;
    transition: 0.3s ease-in-out;
}

.input-valentine:focus {
    border-color: #ff3b5c;
    box-shadow: 0 0 10px rgba(255, 107, 129, 0.5);
}

/* Botón con efecto */
.btn-valentine {
    background: #ff3b5c;
    color: #fff;
    padding: 10px 20px;
    border-radius: 15px;
    font-weight: bold;
    transition: 0.3s ease;
    border: none;
}

.btn-valentine:hover {
    background: #ff6b81;
    box-shadow: 0 0 10px rgba(255, 107, 129, 0.6);
}

/* Imágenes animadas */
.valentine-gif {
    width: 100px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(255, 0, 102, 0.5);
}

/* Estilo general del modal */
.group-post-modal {
    border-radius: 15px;
    background: linear-gradient(135deg, #7b61c0, #4a90e2); /* Colores más suaves */
    color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra suave para resaltar el modal */
}

/* Encabezado del modal */
.group-post-modal .modal-header {
    border-bottom: 2px solid rgba(255, 255, 255, 0.2); /* Línea de separación más sutil */
    padding: 15px;
    background-color: rgba(255, 255, 255, 0.1); /* Fondo suave y transparente */
}

/* Título del modal */
.group-post-modal .modal-title {
    font-weight: bold;
    font-size: 1.2rem;
    color: #f1f1f1; /* Color blanco suave para el título */
}

/* Estilo de los campos de entrada */
.group-post-modal .input-group-post {
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.4); /* Borde sutil */
    padding: 10px;
    background-color: rgba(255, 255, 255, 0.15); /* Fondo suave */
    color: white;
}

.group-post-modal .input-group-post:focus {
    border-color: #4a90e2; /* Color de borde más suave al enfocar */
    box-shadow: 0 0 5px rgba(74, 144, 226, 0.5); /* Sombra suave en el campo al enfocar */
}

/* Botón de envío */
.btn-group-post {
    background-color: #ff61a6; /* Rosa suave */
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
    border: none;
}

.btn-group-post:hover {
    background-color: #e91e63; /* Cambio a un tono rosa ligeramente más oscuro */
}

/* Imagen decorativa */
.group-post-gif {
    width: 100px;
    height: auto;
    border-radius: 8px;
    border: 2px solid rgba(255, 255, 255, 0.3); /* Bordes suaves para la imagen */
    margin-bottom: 20px;
}

/* Estilo del cuerpo del modal */
.group-post-modal .modal-body {
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.1); /* Fondo suave para el cuerpo del modal */
}

.group-post-modal .input-group-file {
    display: flex;
    align-items: center;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.4); /* Borde suave */
    background-color: rgba(255, 255, 255, 0.15); /* Fondo suave */
    padding: 10px;
    color: white;
    font-size: 1rem;
}

.group-post-modal .input-group-file input[type="file"] {
    opacity: 0; /* Ocultar el input real */
    position: absolute; /* Colocarlo fuera de la vista */
    z-index: -1; /* Hacerlo no interactuable */
}

.group-post-modal .input-group-file label {
    background-color: #4a90e2; /* Color de fondo suave para el botón */
    color: white;
    border-radius: 6px;
    padding: 8px 20px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.group-post-modal .input-group-file label:hover {
    background-color: #357ab8; /* Hover suave */
}

.group-post-modal .input-group-file span {
    margin-left: 10px; /* Espacio entre el texto y el botón */
    color: rgba(255, 255, 255, 0.7); /* Texto más suave */
}

.group-post-modal .input-group-file input[type="file"]:focus + label {
    border-color: #4a90e2; /* Color de borde más suave al enfocar */
    box-shadow: 0 0 5px rgba(74, 144, 226, 0.5); /* Sombra suave al enfocar */
}

/* Estilo de los campos al lado de la carga de archivos */
.group-post-modal .form-label {
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9); /* Color de los labels en el modal */
}

/* Asegurar que el modal sea responsivo */
@media (max-width: 768px) {
    .group-post-gif {
        width: 80px; /* Reducción de tamaño en pantallas más pequeñas */
    }

    .group-post-modal {
        border-radius: 10px; /* Bordes más pequeños para móviles */
    }
}


/* Estilo general del modal */
.create-post-modal {
    border-radius: 15px;
    background: linear-gradient(135deg, #7b61c0, #4a90e2); /* Colores suaves */
    color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
}

/* Encabezado del modal */
.create-post-modal .modal-header {
    border-bottom: 2px solid rgba(255, 255, 255, 0.2); /* Línea de separación más sutil */
    padding: 15px;
    background-color: rgba(255, 255, 255, 0.1); /* Fondo suave y transparente */
}

/* Título del modal */
.create-post-modal .modal-title {
    font-weight: bold;
    font-size: 1.2rem;
    color: #f1f1f1; /* Blanco suave */
}

/* Estilo de los campos de entrada */
.create-post-modal .form-control {
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.4); /* Borde suave */
    padding: 10px;
    background-color: rgba(255, 255, 255, 0.15); /* Fondo suave */
    color: white;
}

/* Estilo de la caja de texto (textarea) */
.create-post-modal textarea {
    resize: none;
    background-color: rgba(255, 255, 255, 0.1); /* Fondo más suave */
    color: white;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.4); /* Borde sutil */
}

/* Estilo para el campo de carga de archivos */
.create-post-modal .input-group-file {
    display: flex;
    align-items: center;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.4); /* Borde suave */
    background-color: rgba(255, 255, 255, 0.15); /* Fondo suave */
    padding: 10px;
    color: white;
}

.create-post-modal .input-group-file input[type="file"] {
    opacity: 0;
    position: absolute;
    z-index: -1;
}

.create-post-modal .input-group-file label {
    background-color: #4a90e2; /* Color de fondo suave para el botón */
    color: white;
    border-radius: 6px;
    padding: 8px 20px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.create-post-modal .input-group-file label:hover {
    background-color: #357ab8; /* Hover suave */
}

.create-post-modal .input-group-file span {
    margin-left: 10px;
    color: rgba(255, 255, 255, 0.7);
}

/* Botón de publicar */
.create-post-modal .btn-primary {
    background-color: #ff61a6; /* Rosa suave */
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
    border: none;
}

.create-post-modal .btn-primary:hover {
    background-color: #e91e63; /* Hover suave */
}

/* Estilo del cuerpo del modal */
.create-post-modal .modal-body {
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.1); /* Fondo suave */
}

/* Responsividad */
@media (max-width: 768px) {
    .create-post-modal .modal-content {
        border-radius: 10px; /* Bordes más pequeños en pantallas móviles */
    }

    .create-post-modal .input-group-file label {
        padding: 8px 15px; /* Ajuste de tamaño del botón en dispositivos móviles */
    }
}


</style>
