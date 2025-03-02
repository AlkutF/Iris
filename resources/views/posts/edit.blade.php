@extends('layouts.app')

@section('content')
<div class="container mt-5">
        <div class="card shadow-lg border-0 rounded">
            <div class="card-header text-center">
                <h2 class="mb-0">{{ __('Editar Publicación') }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Contenido del post -->
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            <i class="fas fa-pencil-alt"></i> {{ __('Contenido') }}
                        </label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Mostrar imagen existente -->
                    @if ($post->media_url)
                        <div class="mb-3">
                            <label>{{ __('Imagen actual:') }}</label>
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $post->media_url) }}" alt="Imagen del post" class="img-fluid">
                            </div>
                            
                            <!-- Opción para eliminar la imagen -->
                            <div class="form-check">
                                <input type="checkbox" name="remove_media" id="remove_media" class="form-check-input">
                                <label class="form-check-label" for="remove_media">{{ __('Eliminar imagen') }}</label>
                            </div>
                        </div>
                    @endif

                    <!-- Cargar una nueva imagen -->
                    <div class="mb-3">
                        <label for="media" class="form-label">
                            <i class="fas fa-image"></i> {{ __('Cambiar imagen') }}
                        </label>
                        <input type="file" name="media" id="media" class="form-control @error('media') is-invalid @enderror">
                        @error('media')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Lista de intereses con los intereses seleccionados -->
                    <div class="mb-3">
                        <label for="interests" class="form-label">
                            <i class="fas fa-tags"></i> {{ __('Selecciona los intereses') }}
                        </label>
                        <div class="form-check" style="max-height: 100px; overflow-y: auto; display: flex; flex-wrap: wrap;">
                            @foreach ($interests as $interest)
                                <div class="form-check me-3 mb-2">
                                    <input 
                                        type="checkbox" 
                                        name="interests[]" 
                                        value="{{ $interest->id }}" 
                                        id="interest_{{ $interest->id }}" 
                                        class="form-check-input" 
                                        {{ in_array($interest->id, $post->interests->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="interest_{{ $interest->id }}">
                                        {{ $interest->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botón de actualización -->
                    <div class="mb-0">
                        <button type="submit" class="btn w-100" style="background-color: #575778; color: white;">
                            <i class="fas fa-sync"></i> {{ __('Actualizar Publicación') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<head>
    <!-- Agrega esto dentro del bloque <head> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

