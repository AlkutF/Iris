@extends('layouts.app')

@section('content')
<div class="container mt-4">
        <div class="card">
            <div class="card-header" style="background-color: #AAADBF; color: white;">
                <i class="fas fa-users me-2"></i>Crear un nuevo grupo
            </div>
            <div class="card-body">
                <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nombre del grupo -->
                    <div class="form-group mb-3">
                        <label for="name"><i class="fas fa-users me-2"></i>Nombre del grupo</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="form-group mb-3">
                        <label for="description"><i class="fas fa-pencil-alt me-2"></i>Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Imagen -->
                    <div class="form-group mb-3">
                        <label for="image"><i class="fas fa-image me-2"></i>Imagen del grupo</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Tipo de grupo -->
                    <div class="form-group mb-3">
                        <label for="type"><i class="fas fa-lock me-2"></i>Tipo de grupo</label>
                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="public" {{ old('type') == 'public' ? 'selected' : '' }}>Público</option>
                            <option value="private" {{ old('type') == 'private' ? 'selected' : '' }}>Privado</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Búsqueda de intereses -->
                    <div class="form-group mb-3">
                        <label for="interestSearch"><i class="fas fa-search me-2"></i>Buscar intereses:</label>
                        <input type="text" id="interestSearch" class="form-control" placeholder="Buscar intereses...">
                    </div>

                    <!-- Lista de intereses con límite de altura y scroll -->
                    <div class="form-group">
                        <label><i class="fas fa-tags me-2"></i>Selecciona los intereses:</label>
                        <div id="interestList" class="form-check" style="max-height: 150px; overflow-y: auto; display: flex; flex-wrap: wrap;">
                            @foreach($interests as $interest)
                                <div class="form-check me-3 mb-2">
                                    <input 
                                        type="checkbox" 
                                        name="interests[]" 
                                        value="{{ $interest->id }}" 
                                        id="interest_{{ $interest->id }}" 
                                        class="form-check-input">
                                    <label class="form-check-label" for="interest_{{ $interest->id }}">
                                        {{ $interest->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botón de "Crear grupo" con el mismo estilo que el botón de "Crear publicación" -->
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #575778; color: white;">
                        <i class="fas fa-plus-circle me-2"></i>Crear grupo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Función de búsqueda para filtrar los intereses
        document.getElementById('interestSearch').addEventListener('input', function () {
            const query = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const allInterests = document.querySelectorAll('.form-check');
            allInterests.forEach(function (interestDiv) {
                const label = interestDiv.querySelector('.form-check-label');
                let text = label.textContent.trim().toLowerCase();
                text = text.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Ignorar tildes
                interestDiv.style.display = text.includes(query) ? 'block' : 'none';
            });
        });
    </script>
@endsection
