@extends('layouts.app')
@include('components.navbar')
@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
               <h1 class="h1-container">Todos los grupos</h1>
                <a href="{{ route('groups.create') }}" class="btn-event float-end" style="display:none">Crear grupo</a>
            </div>
            <div class="card-body">

                            <!-- Formulario de búsqueda -->
                <form method="GET" action="{{ route('groups.index') }}" class="mb-4" id="searchGroupsForm">
                    <div class="d-flex w-100 justify-content-between">
                        <!-- Campo de búsqueda -->
                        <input 
                            type="text" 
                            name="name" 
                            class="input-search w-50" 
                            placeholder="Buscar por nombre" 
                            value="{{ request('name') }}">

                        <!-- Filtro por interés -->
                        <select name="interest" class="input-search w-50 hidden-movile">
                            <option value="">Filtrar por interés</option>
                            @foreach($interests as $interest)
                                <option value="{{ $interest->id }}" {{ request('interest') == $interest->id ? 'selected' : '' }}>
                                    {{ $interest->name }}
                                </option>
                            @endforeach
                        </select>

                <!-- Botones -->
                <div class="d-flex w-50">
                <button type="submit" class="btn-event w-50" style="margin-right:10px">
                    <i class="bi bi-search text-black margin-left"></i> 
                    <span class="d-none d-sm-inline">Buscar</span> <!-- Ocultar en pantallas pequeñas -->
                </button>

                <a href="{{ route('groups.index') }}" class="btn-event w-50 text-center d-flex justify-content-center align-items-center">
                    <i class="bi bi-x-circle text-black margin-left"></i>
                    <span class="d-none d-sm-inline">Limpiar</span> <!-- Ocultar en pantallas pequeñas -->
                </a>
                </div>
            </div>
        </form>
                @if($groups->isEmpty())
                    <p>No hay grupos disponibles.</p>
                @else
                    <div class="row" id="groupsList">
                    @foreach($groups as $group)
    <div class="col-md-4 mb-4 grupo-item">
        <a href="{{ route('groups.show', $group->id) }}" style="text-decoration: none; color: inherit;">
            <div class="preview" 
            style="background-image: url('{{ asset(($group->image_url ?? 'assets/defaultGroup.webp')) }}'); 
       background-size: cover; 
       background-position: center; 
       height: 200px;">

                <div class="preview-footer">
                    <h5>{{ $group->name }}</h5>
                    <p id="previewDescription">
                        <i class="fa-solid fa-users"></i> Descripción: <br>
                        <span class="preview-placeholder">
                            {{ $group->description ?? 'No se ha proporcionado descripción.' }}
                        </span>
                    </p>
                </div>
            </div>
        </a>
    </div>
@endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-wrapper">
                        {{ $groups->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<style>
.margin-left{
    margin-right: 5px;
}
.input-search {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-right: 10px;
    font-size: 16px;
    color: #333;
    outline: none;
    transition: 0.3s;
}
.h1-container{
    text-align: center;
    color: #333;
}

.btn-event {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-event:hover {
    background-color: #0056b3;
}
.card-header{
    display: flex; !important;
    justify-content: space-between; !important;
}
.card-header a{
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.preview {
    position: relative;
    background-color: #f7f7f7;
    padding: 20px;
    border-radius: 10px;
    min-height: 200px;
    background-size: cover;
    background-position: center;
    transition: transform 0.3s;
}

.preview-footer {
    width: 100%;
    background-color: rgba(211, 211, 211, 0.9);
    margin-top: 60px;
    max-height: 140px;
    padding: 15px;
    text-align: center;
    border-radius: 0 0 10px 10px;
}

.preview-footer h5 {
    font-size: 18px;
    color: #333;
    margin-bottom: 10px;
}

.preview-footer p {
    font-size: 14px;
    color: #333;
    margin: 5px 0;
}
.pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .pagination .page-link {
        background-color: #AAADBF; /* Color de fondo de los botones */
        color: white; /* Color de texto */
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 14px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .pagination .page-link:hover {
        background-color: #8A8FB4; /* Color de fondo al hacer hover */
        transform: scale(1.05); /* Efecto de ampliación al hacer hover */
    }

    .pagination .page-item.active .page-link {
        background-color: #5D5F7E; /* Color de fondo del elemento activo */
        border-color: #5D5F7E;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #e0e0e0; /* Fondo gris cuando está deshabilitado */
        color: #aaa; /* Texto gris cuando está deshabilitado */
    }

@media screen and (max-width: 768px) {
    .margin-left{
        margin-right: 0px;
    }
    .preview {
        padding: 10px;
    }

    .preview-footer {
        margin-top: 80px;
        padding: 10px;
    }

    .preview-footer h5 {
        font-size: 16px;
    }

    .preview-footer p {
        font-size: 12px;
    }

    .hidden-movile{ display: none; !important; }
    
}
</style>
