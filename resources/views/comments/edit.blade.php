@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded">
            <div class="card-header text-center">
                <h2 class="mb-0">{{ __('Editar Comentario') }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Contenido del comentario -->
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            <i class="fas fa-pencil-alt"></i> {{ __('Contenido') }}
                        </label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror">{{ old('content', $comment->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Botón de actualización -->
                    <div class="mb-0">
                        <button type="submit" class="btn w-100" style="background-color: #575778; color: white;">
                            <i class="fas fa-sync"></i> {{ __('Actualizar Comentario') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
