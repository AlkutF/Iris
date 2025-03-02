@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Post</h1>
    <form action="{{ route('groups.posts.update', ['group' => $group->id, 'post' => $post->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="content" class="form-label">Contenido</label>
            <textarea name="content" id="content" class="form-control" rows="4">{{ old('content', $post->content) }}</textarea>
        </div>

        <!-- Imagen existente (si existe) -->
        @if($post->media_url)
            <div class="mb-3">
                <label for="image" class="form-label">Imagen Actual</label>
                <div>
                    <img src="{{ asset('storage/' . $post->media_url) }}" alt="Imagen del post" class="img-fluid" width="150">

                </div>
                <div>
                    <!-- Botón para eliminar la imagen -->
                    <label for="remove_image" class="text-danger">Eliminar imagen</label>
                    <input type="checkbox" name="remove_image" id="remove_image" value="1">
                </div>
            </div>
        @endif

        <!-- Subir una nueva imagen -->
        <div class="mb-3">
            <label for="image" class="form-label">Subir Nueva Imagen</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
