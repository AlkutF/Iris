@extends('layouts.app')
@section('content')
<!-- Formulario de edición de perfil -->
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded">
        <div class="card-header text-center">
            <h2 class="mb-0">{{ __('Editar Perfil') }}</h2>
        </div>
        <div class="card-body">
            <form onsubmit="return validateForm()" action="{{ route('profile.update', $profile->user_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nombre del perfil -->
                <div class="mb-3">
                    <label for="nombre_perfil" class="form-label">
                        <i class="fas fa-user"></i> {{ __('Nombre del Perfil') }}
                    </label>
                    <input type="text" name="nombre_perfil" id="nombre_perfil" 
                        class="form-control @error('nombre_perfil') is-invalid @enderror"
                        value="{{ old('nombre_perfil', $profile->nombre_perfil) }}" 
                        placeholder="Ingresa tu nombre de perfil">
                    @error('nombre_perfil')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Carrera -->
                <div class="mb-3">
                    <label for="carrera" class="form-label">
                        <i class="fas fa-graduation-cap"></i> {{ __('Carrera') }}
                    </label>
                    <select name="carrera" id="carrera" class="form-control @error('carrera') is-invalid @enderror">
                        <option value="" disabled selected>Selecciona tu carrera</option>
                        @php
                            $carreras = [
                                "Desarrollo de Software", "Diseño Gráfico", "Entrenamiento Deportivo", 
                                "Educación Inicial", "Mecánica Automotriz", "Educación Básica", 
                                "Electrónica", "Gastronomía", "Redes & Telecomunicaciones", 
                                "Contabilidad y Asesoría Tributaria", "Educación Inclusiva", 
                                "Marketing & Comercio Electrónico", "Talento Humano"
                            ];
                        @endphp
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera }}" {{ old('carrera', $profile->carrera) == $carrera ? 'selected' : '' }}>
                                {{ $carrera }}
                            </option>
                        @endforeach
                    </select>
                    @error('carrera')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Biografía -->
                <div class="mb-3">
                    <label for="bio" class="form-label">
                        <i class="fas fa-align-left"></i> {{ __('Biografía') }}
                    </label>
                    <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="4" placeholder="Escribe algo sobre ti...">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Privacidad -->
                <div class="mb-3">
                    <label for="privacy" class="form-label">
                        <i class="fas fa-lock"></i> {{ __('Privacidad') }}
                    </label>
                    <select name="privacy" id="privacy" class="form-control @error('privacy') is-invalid @enderror">
                        <option value="public" {{ old('privacy', $profile->privacy) == 'public' ? 'selected' : '' }}>Público</option>
                        <option value="private" {{ old('privacy', $profile->privacy) == 'private' ? 'selected' : '' }}>Privado</option>
                    </select>
                    @error('privacy')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Intereses -->
                <div class="mb-3">
                    <label for="interestSearch" class="form-label">
                        <i class="fas fa-search"></i> {{ __('Buscar intereses') }}
                    </label>
                    <input type="text" id="interestSearch" class="form-control" placeholder="Buscar intereses...">
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-tags"></i> {{ __('Selecciona tus intereses') }}</label>
                    <div id="interestList" class="form-check" style="max-height: 150px; overflow-y: auto; display: flex; flex-wrap: wrap;">
                        @foreach ($interests as $interest)
                            <div class="form-check me-3 mb-2">
                                <input type="checkbox" name="interests[]" value="{{ $interest->id }}" 
                                    id="interest_{{ $interest->id }}" class="form-check-input" 
                                    {{ in_array($interest->id, old('interests', $profile->interests->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="form-check-label" for="interest_{{ $interest->id }}">
                                    {{ $interest->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Género -->
                <div class="mb-3">
                    <label for="gender" class="form-label">
                        <i class="fas fa-genderless"></i> {{ __('Género') }}
                    </label>
                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                        <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                        <option value="other" {{ old('gender', $profile->gender) == 'other' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Avatar -->
                <div class="mb-3">
                    <label for="avatar" class="form-label">
                        <i class="fas fa-image"></i> {{ __('Avatar') }}
                    </label>
                    <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror">
                    @if($profile->avatar)
                        <div class="mt-2 text-center">
                            <img src="{{ asset('storage/' . $profile->avatar) }}" alt="Avatar" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        </div>
                    @endif
                    @error('avatar')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Botón de actualización -->
                <div class="mb-0">
                    <button type="submit" class="btn w-100" style="background-color: #575778; color: white;">
                        <i class="fas fa-save"></i> {{ __('Actualizar perfil') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


    <script>
  function validateForm() {
    let nombrePerfil = document.getElementById("nombre_perfil").value.trim();
    let regex = /^[a-zA-Z\s]{3,20}$/;

    if (!regex.test(nombrePerfil)) {
        alert("El nombre de perfil debe tener entre 3 y 20 caracteres y solo contener letras.");
        return false;
    }
    return true;
}
        // Función de búsqueda para filtrar los intereses
        document.getElementById('interestSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const allInterests = document.querySelectorAll('.form-check');
            allInterests.forEach(function(interestDiv) {
                const label = interestDiv.querySelector('.form-check-label');
                let text = label.textContent.trim().toLowerCase();
                // Normalizar el texto de interés y la búsqueda para ignorar tildes
                text = text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                // Comparar la búsqueda con el texto de interés
                if (text.includes(query)) {
                    interestDiv.style.display = 'block';
                } else {
                    interestDiv.style.display = 'none';
                }
            });
        });
    </script>
@endsection
