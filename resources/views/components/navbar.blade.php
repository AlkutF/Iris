<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        /* Estilos para la barra de navegación */
        .navbar {
            background: rgb(66, 135, 209); /* Azul vibrante */
            background: linear-gradient(90deg, rgb(66, 135, 209) 0%, rgb(66, 135, 209) 100%);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Estilos para los iconos */
        .nav-link {
            color: white !important;
            font-size: 1.3rem;
            transition: 0.3s ease-in-out;
        }

        .nav-link:hover {
            color: #ffeb3b !important; /* Amarillo al pasar el mouse */
            transform: scale(1.1);
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Imagen de perfil en navbar */
        .img-logo {
            max-width: 35px;
            max-height: 35px;
            border-radius: 50%;
            padding: 1px;
            border: 2px solid white;
            transition: 0.3s;
        }

        .img-logo:hover {
            transform: scale(1.1);
        }

        /* Centrar opciones en dispositivos móviles */
        @media (max-width: 991px) {
            .navbar-nav {
                text-align: center;
                width: 100%;
            }
            .navbar-collapse {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <!-- Logo -->
        <img src="{{ asset('storage/assets/Logo.webp') }}" alt="Logo de la Red Social" class="img-logo">
        <a class="navbar-brand text-white fw-bold" href="/home">{{ config('globalvars.nombre_red_social') }}</a>

        <!-- Botón de colapsar -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido del navbar -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link bi bi-house" href="/home"> Inicio</a></li>
                <li class="nav-item"><a class="nav-link bi bi-person-check" href="{{ route('amistades.index') }}"> Amigos</a></li>
                <li class="nav-item"><a class="nav-link bi bi-people" href="{{ route('users.index') }}"> Usuarios</a></li>
                <li class="nav-item"><a class="nav-link bi bi-people-fill" href="{{ route('groups.index') }}"> Grupos</a></li>
            </ul>

            <!-- Chats, Perfil, Cerrar sesión -->
            <ul class="navbar-nav d-lg-flex justify-content-lg-end">
                <li class="nav-item">
                    <a class="nav-link bi bi-chat" href="{{ route('chats.index') }}"> Chats</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="Perfil" class="img-logo" style="max-width: 30px; max-height: 30px;"> Perfil
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item bi bi-box-arrow-in-right" href="{{ route('closesesion') }}"> Cerrar sesión</a>
                        <a class="dropdown-item bi bi-person" href="{{ route('profile.edit', auth()->user()->id) }}"> Editar perfil</a>
                        <a class="dropdown-item bi bi-person" href="{{ url('/profile/' . auth()->user()->id) }}"> Ver mi perfil</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
