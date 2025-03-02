<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Grupos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Solicitudes de Grupos</h1>

        <div class="mt-4">
            <h2>Posteos Pendientes</h2>
            @if($posts->isEmpty())
                <p>No hay posteos pendientes.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Contenido</th>
                            <th>Usuario</th>
                            <th>Imagen</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->content }}</td>
                                <td>{{ $post->user->name }}</td>

                                <!-- Mostrar imagen si existe -->
                                <td>
                                    @if($post->media_url)
                                    <a href="{{ asset('storage/' . $post->media_url) }}" target="_blank">
    Ver Imagen
</a>
                                    @else
                                        <span>No hay imagen</span>
                                    @endif
                                </td>

                                <td>
                                    <!-- Botón para permitir post -->
                                    <form action="{{ route('groups.allowPost', $post->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-success btn-sm">Permitir</button>
                                    </form>

                                    <!-- Botón para denegar post -->
                                    <form action="{{ route('groups.denyPost', $post->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Denegar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html>
