@extends('layouts.app')
@include('components.navbar')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header text-white text-center" style="background-color: rgb(66, 135, 209);">
            <h1 class="h1-container">Mis Amigos</h1>
        </div>
        <div class="card-body">
            <!-- Formulario de búsqueda -->
            <div class="row mb-4">
                <form id="searchFriendsForm" class="d-flex w-100">
                    <input type="text" id="searchFriendsName" name="name" class="input-search" placeholder="Buscar amigo por nombre">
                    <button type="button" id="searchFriendsButton" class="btn btn-outline-primary">Buscar</button>
                    <button type="button" id="resetFriendsButton" class="btn btn-outline-primary">Limpiar</button>
                </form>
            </div>
            <div class="row" id="friendsList">
                @if($friends->isEmpty())
                    <div class="col-12 text-center mt-4">
                        <p class="message">Ser parte de IRIS es mejor con amigos</p>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-info">Busca amigos</a>
                    </div>
                @else
                    @foreach ($friends as $friend)
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('profile.show', $friend->id) }}" class="friend-card">
                                <div class="preview" style="background-image: url('{{ asset('storage/' . ($friend->profile->avatar ?? 'assets/default.webp')) }}');">
                                    <div class="preview-footer">
                                        <h5>{{ $friend->name }}</h5>
                                        <p id="previewBio">
                                            <i class="fa-solid fa-user"></i> Un poco sobre mí:<br>
                                            <span class="preview-placeholder">
                                                {{ $friend->profile->bio ?? 'Aún no has agregado una pequeña descripción de ti.' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <div class="pagination-wrapper">
            {{ $friends->links('pagination::bootstrap-4') }}
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

.message {
    font-size: 24px;
    text-align: center;
    font-weight: bold;
    color: #333;
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
    padding: 12px 24px;
    background-color: rgb(66, 135, 209);
    color: white;
    border-radius: 8px;
    font-size: 16px;
    transition: 0.3s;
    font-weight: bold;
}

.btn-event:hover {
    background-color: rgb(44, 115, 194);
}

.h1-container {
    font-size: 28px;
    font-weight: bold;
}

.friend-card {
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s ease-in-out;
}

.friend-card:hover {
    transform: scale(1.05);
}

.preview {
    position: relative;
    background-size: cover;
    background-position: center;
    height: 400px;
    display: flex;
    align-items: flex-end;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s;
}

.preview-footer {
    width: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    padding: 15px;
    text-align: center;
    border-radius: 0 0 12px 12px;
}
</style>

@push('scripts')
@endpush
