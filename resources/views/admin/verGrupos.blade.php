<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Grupos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Listado de Grupos</h1>

        <!-- Botón para Crear Grupos -->
        <a href="{{ route('groups.create') }}" class="btn btn-primary mt-3">Crear Grupo</a>

        <div class="mt-4">
            <h2>Grupos Disponibles</h2>
            @if($groups->isEmpty())
                <p>No hay grupos creados aún.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                            <tr>
                                <td>{{ $group->id }}</td>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->description }}</td>

                                <!-- Mostrar la imagen si existe -->
                                <td>
                                    @if($group->media_url)
                                        <a href="{{ asset($group->media_url) }}" target="_blank">
                                            <img src="{{ asset($group->media_url) }}" alt="Imagen del grupo" style="width: 100px; height: 100px; object-fit: cover;">
                                        </a>
                                    @else
                                        <span>No hay imagen</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('groups.show', $group->id) }}" class="btn btn-info btn-sm">Ver</a>
                                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <!-- Botón para eliminar grupo, si es necesario -->
                                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
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
