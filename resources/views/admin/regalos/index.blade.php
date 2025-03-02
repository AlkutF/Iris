<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Regalos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Listado de Regalos</h1>
        
        @if($regalos->isEmpty())
            <p>No hay regalos creados aún.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la Pareja</th>
                        <th>Carrera</th>
                        <th>Semestre</th>
                        <th>Anonimato</th>
                        <th>Usuario</th>
                        <th>Email Usuario</th>
                        <th>Nombre del Perfil</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regalos as $regalo)
                        <tr>
                            <td>{{ $regalo->id }}</td>
                            <td>{{ $regalo->nombre_pareja }}</td>
                            <td>{{ $regalo->carrera }}</td>
                            <td>{{ $regalo->semestre }}</td>
                            <td>{{ $regalo->anonimato ? 'Sí' : 'No' }}</td>
                            <td>{{ $regalo->user->name }}</td>
                            <td>{{ $regalo->user->email }}</td>
                            <td>{{ $regalo->user->profile->nombre_perfil ?? 'Sin perfil' }}</td>
                            <td>{{ $regalo->created_at }}</td>
                            <td>
                                <form action="{{ route('regalos.destroy', $regalo->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este regalo?');">
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
</body>
</html>
