<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posteos de {{ $user->name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Posteos de {{ $user->name }}</h1>

        <a href="{{ route('admin.users') }}" class="btn btn-secondary mb-3">Volver a Usuarios</a>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if($posts->isEmpty())
            <p>Este usuario no tiene publicaciones.</p>
        @else
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Contenido</th>
                        <th>Tipo de Medio</th>
                        <th>Media</th>
                        <th>Fecha Creación</th>
                        <th>Última Actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->content }}</td>
                            <td>{{ $post->media_type ?? 'N/A' }}</td>
                            <td>
                                @if($post->media_url)
                                    @if($post->media_type === 'image')
                                        <img src="{{ asset('storage/' . $post->media_url) }}" alt="Imagen" class="img-thumbnail" width="100">
                                    @elseif($post->media_type === 'video')
                                        <video width="150" controls>
                                            <source src="{{ asset('storage/' . $post->media_url) }}" type="video/mp4">
                                            Tu navegador no soporta videos.
                                        </video>
                                    @else
                                        <a href="{{ asset('storage/' . $post->media_url) }}" target="_blank">Ver Archivo</a>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $post->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <!-- Formulario para eliminar post -->
                                <form action="{{ route('admin.posts.delete', $post) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
