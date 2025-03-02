<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Panel de Administración</h1>
        <a href="{{ route('admin.users') }}" class="btn btn-primary mt-3">Ver Usuarios</a>

        <!-- Botón para crear grupos -->
        <a href="{{ route('groups.create') }}" class="btn btn-primary mt-3">Crear Grupo</a>

        <!-- Botón para ver grupos -->
        <a href="{{ route('groups.index_admin_grupos') }}" class="btn btn-secondary mt-3">Ver Grupos</a>

        <!-- Botón para ver solicitudes de grupos -->
        <a href="{{ route('admin.verSolicitudes') }}" class="btn btn-warning mt-3">Ver Solicitudes de Grupos</a>
   
        <!-- Botón para ver regalos -->
<a href="{{ route('admin.regalos') }}" class="btn btn-info mt-3">Ver Regalos</a>
    </div>
</body>
</html>
