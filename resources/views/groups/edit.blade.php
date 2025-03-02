@extends('layouts.app')
@include('components.navbar')
@section('content')
<div class="container mt-4">

        <div class="card">
            <div class="card-header" style="background-color: #AAADBF; color: white;">
                <i class="fas fa-users me-2"></i>Editar Grupo
            </div>
            <div class="card-body">
                <form action="{{ route('groups.update', $group->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nombre del grupo -->
                    <div class="form-group mb-3">
                        <label for="name"><i class="fas fa-users me-2"></i>Nombre del Grupo</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $group->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="form-group mb-3">
                        <label for="description"><i class="fas fa-pencil-alt me-2"></i>Descripción</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $group->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Tipo de grupo -->
                    <div class="form-group mb-3">
                        <label for="type"><i class="fas fa-lock me-2"></i>Tipo de Grupo</label>
                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="public" {{ old('type', $group->type) == 'public' ? 'selected' : '' }}>Público</option>
                            <option value="private" {{ old('type', $group->type) == 'private' ? 'selected' : '' }}>Privado</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Imagen del grupo -->
                    <div class="form-group mb-3">
                        <label for="image"><i class="fas fa-image me-2"></i>Imagen del Grupo</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                        @if($group->image_url)
                            <img src="{{ asset('storage/' . $group->image_url) }}" alt="Imagen actual" class="img-thumbnail mt-2" width="150">
                        @endif
                        @error('image')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Botón de guardar cambios -->
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #575778; color: white;">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
