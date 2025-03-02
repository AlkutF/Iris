@extends('layouts.app')

@section('content')
@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded">
            <div class="card-header text-center">
                <h2 class="mb-0">{{ __('Crear Publicación') }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Contenido -->
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            <i class="fas fa-pencil-alt"></i> {{ __('Contenido') }}
                        </label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="4" placeholder="Escribe el contenido de tu publicación..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Vista previa del contenido -->
                    <div class="mb-3">
                        <label for="preview" class="form-label">
                            <i class="fas fa-eye"></i> {{ __('Vista previa del contenido') }}
                        </label>
                        <div id="preview" class="border p-3" style="background-color: #f7f7f7; min-height: 100px;">
                            <p id="contentPreview">El contenido se mostrará aquí...</p>
                        </div>
                    </div>

                    <!-- Multimedia -->
                    <div class="mb-3">
                        <label for="media" class="form-label">
                            <i class="fas fa-image"></i> {{ __('Multimedia (Imagen/Video)') }}
                        </label>
                        <input type="file" name="media" id="media" class="form-control @error('media') is-invalid @enderror" accept="image/*,video/*">
                        @error('media')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <!-- Vista previa de la multimedia -->
                    <div id="mediaPreview" style="margin-top: 15px;">
                        <!-- Vista previa de la imagen o video se insertará aquí -->
                    </div>

                    <!-- Búsqueda de intereses -->
                    <div class="mb-3">
                        <label for="interestSearch" class="form-label">
                            <i class="fas fa-search"></i> {{ __('Buscar intereses') }}
                        </label>
                        <input type="text" id="interestSearch" class="form-control" placeholder="Buscar intereses...">
                    </div>

                    <!-- Lista de intereses con filtro -->
                    <div class="mb-3">
                        <label for="interests" class="form-label">
                            <i class="fas fa-tags"></i> {{ __('Selecciona tus intereses') }}
                        </label>
                        <div id="interestList" class="form-check" style="max-height: 150px; overflow-y: auto; display: flex; flex-wrap: wrap;">
                            @foreach ($interests as $interest)
                                <div class="form-check me-3 mb-2">
                                    <input 
                                        type="checkbox" 
                                        name="interests[]" 
                                        value="{{ $interest->id }}" 
                                        id="interest_{{ $interest->id }}" 
                                        class="form-check-input" 
                                        {{ in_array($interest->id, old('interests', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="interest_{{ $interest->id }}">
                                        {{ $interest->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botón de publicación -->
                    <div class="mb-0">
                        <button type="submit" class="btn w-100" style="background-color: #575778; color: white;">
                            <i class="fas fa-paper-plane"></i> {{ __('Crear Publicación') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Crear Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="4" placeholder="¿Qué estás pensando?" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="media" class="form-label">Subir Imagen/Video</label>
                        <input type="file" name="media" class="form-control" accept="image/*,video/*">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Publicar</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script>
        // Función de búsqueda para filtrar los intereses
        document.getElementById('interestSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const allInterests = document.querySelectorAll('.form-check');
            allInterests.forEach(function(interestDiv) {
                const label = interestDiv.querySelector('.form-check-label');
                let text = label.textContent.trim().toLowerCase();
                text = text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                if (text.includes(query)) {
                    interestDiv.style.display = 'block';
                } else {
                    interestDiv.style.display = 'none';
                }
            });
        });

        // Vista previa del contenido
        document.getElementById('content').addEventListener('input', function() {
            const preview = document.getElementById('contentPreview');
            preview.textContent = this.value ? this.value : 'El contenido se mostrará aquí...';
        });

        // Vista previa de la imagen o video
        document.getElementById('media').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const mediaPreview = document.getElementById('mediaPreview');
            mediaPreview.innerHTML = '';

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileType = file.type.split('/')[0];
                    let previewContent = '';

                    if (fileType === 'image') {
                        previewContent = `<img src="${e.target.result}" alt="Vista previa" class="img-fluid" style="max-height: 300px;">`;
                    } else if (fileType === 'video') {
                        previewContent = `<video controls class="img-fluid" style="max-height: 300px;"><source src="${e.target.result}" type="${file.type}"></video>`;
                    }

                    mediaPreview.innerHTML = previewContent;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection

<style>

.interestList{
    max-height: 200px;
    overflow-y: auto;
 }
</style>