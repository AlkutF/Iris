@extends('layouts.app')

@section('content')
<div class="container mt-4">
        <h2 class="text-center mb-4">Crear una nueva publicación para el grupo: {{ $group->name }}</h2>
        
        <div class="card">
            <div class="card-header" style="background-color: #AAADBF; color: white;">
                <i class="fas fa-pencil-alt me-2"></i>Crear Publicación
            </div>
            <div class="card-body">
                <!-- Formulario para crear la publicación -->
                <form action="{{ route('group.posts.store', $group->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Contenido de la publicación -->
                    <div class="form-group mb-3">
                        <label for="content"><i class="fas fa-pen me-2"></i>Contenido de la publicación</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Subir imagen o video -->
                    <div class="form-group mb-3">
                        <label for="media_url"><i class="fas fa-upload me-2"></i>Subir imagen o video (opcional)</label>
                        <input type="file" class="form-control @error('media_url') is-invalid @enderror" id="media_url" name="media_url" accept="image/*,video/*">
                        @error('media_url')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Botón de publicar -->
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #575778; color: white;">
                        <i class="fas fa-share me-2"></i>Publicar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
