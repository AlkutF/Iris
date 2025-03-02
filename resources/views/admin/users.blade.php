<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Lista de Usuarios</h1>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <!-- Botón para ver posteos -->
                            <a href="{{ route('admin.users.posts', $user) }}" class="btn btn-info">Ver Post</a>
                            
                            <!-- Formulario para banear/desbanear usuario -->
                            <form action="{{ route('admin.users.ban', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-{{ $user->banned_at ? 'success' : 'danger' }}">
                                    {{ $user->banned_at ? 'Desbanear' : 'Bannear Usuario' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
