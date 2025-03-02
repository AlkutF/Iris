<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('globalvars.nombre_red_social') }}</title>
    <link rel="icon" href="{{ asset('storage/assets/icons/favicon.ico') }}?v=1" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Cargar Bootstrap 4.3.1 CSS -->

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d64bc9fded.js" crossorigin="anonymous"></script>
</head>
<body>


<div class="container" >
    @yield('content')
</div>

<!-- Cargar jQuery completo antes de Bootstrap y Popper -->

<!-- Los scripts adicionales de cada página -->
@yield('scripts')
@stack('scripts')
</body>
</html>
