@extends('layouts.app')
@include('components.navbar')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header text-white text-center" style="background-color: rgb(66, 135, 209);">
            <h1 class="h1-container">Usuarios</h1>
        </div>
        <div class="card-body">
            <!-- Formulario de búsqueda -->
            <div class="row mb-4">
                <form id="searchForm" class="d-flex w-100">
                    <input type="text" id="searchName" name="name" class="input-search" placeholder="Buscar por nombre">
                    <button type="button" id="searchButton" class="btn btn-outline-primary">Buscar</button>
                    <button type="button" id="resetButton" class="btn btn-outline-primary">Limpiar</button>
                </form>
            </div>

            <!-- Resultados de usuarios -->
            <div class="row" id="userResults">
                @foreach ($users as $user)
                    @if ($user->profile && $user->profile->avatar)
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('profile.show', $user->id) }}" style="text-decoration: none; color: inherit;">
                                <div class="preview" style="position: relative; background-image: url('{{ asset('storage/' . ($user->profile->avatar ?? 'assets/default.webp')) }}'); background-size: cover; background-position: center; height: 400px; display: flex; align-items: flex-end; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); transition: transform 0.3s, box-shadow 0.3s;">
                                    <div class="preview-footer" style="width: 100%; background-color: rgba(211, 211, 211, 0.8); padding: 15px; text-align: center; border-radius: 0 0 10px 10px;">
                                        <h5 style="font-size: 20px; font-weight: bold; color: #333; margin-bottom: 10px;">{{ $user->profile->nombre_perfil }}</h5>
                                        <p id="previewBio" style="font-size: 14px; color: #555; margin: 5px 0;">
                                            <i class="fa-solid fa-user"></i> Un poco sobre mí: <br>
                                            <span class="preview-placeholder" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                {{ $user->profile->bio ?? 'Aún no has agregado una pequeña descripción de ti.' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            <div class="pagination-wrapper">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@endsection

<style>
body {
    background-color: #f4f7fc;
    font-family: 'Arial', sans-serif;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.input-search {
    width: 100%;
    padding: 12px;
    border: 2px solid rgb(66, 135, 209);
    border-radius: 8px;
    font-size: 16px;
    transition: 0.3s;
}

.input-search:focus {
    border-color: rgb(66, 135, 209);
    box-shadow: 0 0 8px rgba(66, 135, 209, 0.5);
}

.btn-event {
    padding: 12px 25px;
    background-color: rgb(66, 135, 209);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    font-size: 16px;
}

.btn-event:hover {
    background-color: rgb(35, 96, 177);
}

.h1-container {
    font-size: 28px;
    font-weight: bold;
}

.preview {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.preview:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

.preview-footer h5 {
    font-weight: 600;
    color: rgb(66, 135, 209);
}

.preview-footer p {
    font-size: 14px;
    color: #333;
    line-height: 1.5;
}

.preview-placeholder {
    color: #555;
}

.card-header {
    border-radius: 10px 10px 0 0;
}

.pagination-wrapper .pagination {
    justify-content: center;
}

.pagination-wrapper .page-link {
    color: rgb(66, 135, 209);
    border-color: rgb(66, 135, 209);
}

.pagination-wrapper .page-link:hover {
    background-color: rgb(66, 135, 209);
    color: white;
}
</style>


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Manejar el clic en el botón de búsqueda
        $('#searchButton').click(function () {
            let name = $('#searchName').val(); // Obtener el valor del input
            fetchResults({ name: name });
        });

        // Manejar el clic en el botón de limpiar
        $('#resetButton').click(function () {
            $('#searchName').val(''); // Limpiar el input
            fetchResults({}); // Solicitar todos los resultados
        });

        // Función para hacer la solicitud AJAX
        function fetchResults(params) {
            $.ajax({
                url: "{{ route('users.index') }}", // Ruta de la consulta
                type: "GET",
                data: params, // Parámetros de búsqueda
                success: function (data) {
                    $('#userResults').html(data); // Actualizar solo el área de resultados
                },
                error: function (xhr) {
                    console.error("Error al obtener los datos:", xhr.responseText);
                }
            });
        }
    });
</script>
@endsection
