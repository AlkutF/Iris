@extends('layouts.app')
@include('components.navbar')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>{{ $post->title }}</h2>
        </div>

        <div class="card-body">
            <p><strong>Publicado por:</strong> {{ $post->user->name }}</p>
            <p><strong>Fecha de publicación:</strong> {{ $post->created_at->format('d/m/Y') }}</p>

            <div class="mt-3">
                <h5>Contenido:</h5>
                <p>{{ $post->content }}</p>
            </div>

            <!-- Reacciones -->
            <div class="mt-3">
                <h5>Reacciones:</h5>
                <div id="reactions-list"></div> <!-- Contenedor para insertar las reacciones dinámicamente -->
            </div>

            <!-- Agregar comentarios si los tienes -->
            <div class="mt-3">
                <h5>Comentarios:</h5>
                <ul>
                    @foreach ($post->comments as $comment)
                        <li>
                            <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Formulario para dejar un comentario -->
            <form method="POST" action="{{ route('comments.store', $post->id) }}">
                @csrf
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" placeholder="Deja un comentario..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Comentar</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Datos de reacciones agrupadas (pasadas desde el controlador)
    const reactionsData = @json($reactionsGrouped);

    // Obtener el contenedor de reacciones
    const reactionsListContainer = document.getElementById('reactions-list');

    // Iterar sobre las reacciones agrupadas y agregar HTML dinámicamente
    for (const [reactionType, reactions] of Object.entries(reactionsData)) {
        // Crear un párrafo con el tipo de reacción
        const reactionTypeParagraph = document.createElement('p');
        
        // Mostrar el tipo de reacción y la cantidad de personas que reaccionaron
        reactionTypeParagraph.innerHTML = `<strong>${capitalize(reactionType)}</strong> 
            ${reactions.length} ${reactions.length > 1 ? 'personas' : 'persona'} han reaccionado.`;
        
        // Agregar el párrafo al contenedor
        reactionsListContainer.appendChild(reactionTypeParagraph);

        // Crear una lista para los usuarios que reaccionaron
        const usersList = document.createElement('ul');

        reactions.forEach(reaction => {
            const listItem = document.createElement('li');

            // Crear el enlace al perfil del usuario
            const userLink = document.createElement('a');
            userLink.href = `/profile/${reaction.user.id}`;  // Ruta de perfil de usuario
            userLink.textContent = `${reaction.user.name} reaccionó con ${capitalize(reaction.reaction_type)}`;

            // Añadir el enlace al elemento de la lista
            listItem.appendChild(userLink);
            usersList.appendChild(listItem);
        });

        // Añadir la lista de usuarios al contenedor
        reactionsListContainer.appendChild(usersList);
    }

    // Función para capitalizar la primera letra de la reacción
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endsection
