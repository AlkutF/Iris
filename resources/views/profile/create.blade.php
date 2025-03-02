@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create_perfil.css') }}"><div class="container" style="margin-top: 30px;">
    <div class="row">
        <!-- Columna de formulario -->
        <div class="col-md-6 margin-top-container">
            <div class="container-data">
                <h2><i class="fa-solid fa-user-circle"></i> Crear perfil</h2>
                <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data" id="createProfileForm">
                    @csrf
                    <div class="form-group">
                        <label for="nombre_perfil"><i class="fa-solid fa-id-badge"></i> Nombre de perfil:</label>
                        <input type="text" name="nombre_perfil" id="nombre_perfil" class="form-control" 
                            placeholder="Ejemplo: Juan" value="{{ old('nombre_perfil') }}">
                        @error('nombre_perfil')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="carrera"><i class="fa-solid fa-graduation-cap"></i> ¿Cuál es tu carrera?</label>
                        <select name="carrera" id="carrera" class="form-control">
                        <option value="Desarrollo de Software" {{ old('carrera') == 'Desarrollo de Software' ? 'selected' : '' }}>Desarrollo de Software</option>
<option value="Diseño Gráfico" {{ old('carrera') == 'Diseño Gráfico' ? 'selected' : '' }}>Diseño Gráfico</option>
<option value="Entrenamiento Deportivo" {{ old('carrera') == 'Entrenamiento Deportivo' ? 'selected' : '' }}>Entrenamiento Deportivo</option>
<option value="Educación Inicial" {{ old('carrera') == 'Educación Inicial' ? 'selected' : '' }}>Educación Inicial</option>
<option value="Mecánica Automotriz" {{ old('carrera') == 'Mecánica Automotriz' ? 'selected' : '' }}>Mecánica Automotriz</option>
<option value="Educación Básica" {{ old('carrera') == 'Educación Básica' ? 'selected' : '' }}>Educación Básica</option>
<option value="Electrónica" {{ old('carrera') == 'Electrónica' ? 'selected' : '' }}>Electrónica</option>
<option value="Gastronomía" {{ old('carrera') == 'Gastronomía' ? 'selected' : '' }}>Gastronomía</option>
<option value="Redes & Telecomunicaciones" {{ old('carrera') == 'Redes & Telecomunicaciones' ? 'selected' : '' }}>Redes & Telecomunicaciones</option>
<option value="Contabilidad y Asesoría Tributaria" {{ old('carrera') == 'Contabilidad y Asesoría Tributaria' ? 'selected' : '' }}>Contabilidad y Asesoría Tributaria</option>
<option value="Educación Inclusiva" {{ old('carrera') == 'Educación Inclusiva' ? 'selected' : '' }}>Educación Inclusiva</option>
<option value="Marketing & Comercio Electrónico" {{ old('carrera') == 'Marketing & Comercio Electrónico' ? 'selected' : '' }}>Marketing & Comercio Electrónico</option>
<option value="Talento Humano" {{ old('carrera') == 'Talento Humano' ? 'selected' : '' }}>Talento Humano</option>

                        </select>
                        @error('carrera')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bio"><i class="fa-solid fa-pencil-alt"></i> Cuéntanos un poco sobre ti:</label>
                        <textarea placeholder="Una breve descripción de ti mismo que los demás usuarios podrán ver." name="bio" id="bio" class="form-control">{{ old('bio') }}</textarea>
                        @error('bio')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="privacy"><i class="fa-solid fa-lock"></i> ¿Quién podrá ver tu perfil?</label>
                        <select name="privacy" id="privacy" class="form-control">
                            <option value="public" {{ old('privacy', 'public') == 'public' ? 'selected' : '' }}>Todos los usuarios podrán ver tus publicaciones.</option>
                            <option value="private" {{ old('privacy', 'public') == 'private' ? 'selected' : '' }}>Solo tus amigos podrán ver tu perfil y publicaciones.</option>
                        </select>
                        @error('privacy')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <p class="explanation-message"><i class="fa-solid fa-info-circle"></i> La privacidad del perfil limitará a que solo tu lista de amigos pueda ver tus datos y publicaciones.</p>
                        <p class="explanation-message second"><i class="fa-solid fa-eye-slash"></i> Los usuarios te podrán ver si te buscan, pero tu información será privada hasta que sean tus amigos.</p>
                    </div>

                    <!-- Lista de intereses con límite de altura y scroll -->
                    <div class="form-group">
    <label for="interests"><i class="fa-solid fa-heart"></i> ¿Qué te interesa?:</label>
    <div id="interestList" class="form-check" style="max-height: 100px; overflow-y: auto; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        @foreach ($interests as $interest)
            <div class="form-check" style="display: inline-flex; align-items: center; margin-right: 10px;">
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
        @error('interests')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

                    <!-- Búsqueda de intereses -->
                    <div class="form-group" style="display:none;">
                        <label for="interestSearch"><i class="fa-solid fa-search"></i> Busca más intereses:</label>
                        <input type="text" id="interestSearch" class="form-control" placeholder="Buscar tu interés Ejemplo: Anime">
                    </div>

                    <div class="form-group">
                        <label for="gender"><i class="fa-solid fa-venus-mars"></i> ¿Cual es tu genero?</label>
                        <select name="gender" id="gender" class="form-control">
                        <option value="male" {{ old('gender', 'male') == 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femenino</option>
                    </select>
                        @error('gender')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="avatar"><i class="fa-solid fa-image"></i> Elige tu foto de perfil:</label>
                        <input type="file" name="avatar" id="avatar" class="form-control">
                    </div>
                    @error('interests')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <button type="submit" class="btn-event width-btn-100" id="submitBtn">
                        <i class="fa-solid fa-save"></i> Guardar perfil
                    </button>
                </form>
            </div>
        </div>

        <!-- Columna de previsualización -->
        <div class="col-md-6">
    <div class="preview" id="previewContainer" style="background-image: url('{{ asset('storage/assets/default.webp') }}'); background-size: cover; background-position: center; margin-top: 10%; height: 80%; align-items: center; justify-content: center; border-radius: 10px;">
        <div class="preview-footer" style="position: absolute; bottom: 0; width: 100%; background-color: #fff; padding: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
            <p id="previewProfileName" style="font-size: 14px; color: #333; margin: 5px 0;">
                <i class="fa-solid fa-id-badge"></i> Nombre de perfil: <br> 
                <span class="preview-placeholder">Aún no has agregado un nombre de perfil.</span>
            </p>
            <p id="previewBio" style="font-size: 14px; color: #333; margin: 5px 0;">
                <i class="fa-solid fa-user"></i> Un poco sobre mí: <br>
                <span class="preview-placeholder">Aún no has agregado una pequeña descripción de ti.</span>
            </p>
            <p id="previewCareer" style="font-size: 14px; color: #333; margin: 5px 0;">
                <i class="fa-solid fa-graduation-cap"></i> Carrera: <br>
                <span class="preview-placeholder">Desarrollo de Software</span>
            </p>
            <p id="previewPrivacy" style="font-size: 14px; color: #333; margin: 5px 0;">
                <i class="fa-solid fa-lock"></i> Privacidad: 
                <span class="preview-placeholder">Todos los usuarios podrán ver tus publicaciones.</span>
            </p>
            <p id="previewInterests" style="font-size: 14px; color: #333; margin: 5px 0;">
                <i class="fa-solid fa-heart"></i> Intereses:<br> 
                <span class="preview-placeholder">Aún no has seleccionado tus intereses.</span>
            </p>
            <p id="previewGender" style="font-size: 14px; color: #333; margin: 5px 0;">
                <i class="fa-solid fa-venus-mars"></i> Género: 
                <span class="preview-placeholder">Masculino.</span>
            </p>
        </div>
    </div>
</div>

    </div>
</div>

<style>
    /* Colores pastel suaves para los iconos */
    .fa-solid {
        color: #A2CDB0; /* Verde menta pastel */
    }

    .fa-user-circle { color:rgb(247, 137, 153); } /* Rosa pastel */
    .fa-pencil-alt { color:rgb(125, 197, 221); } /* Azul pastel */
    .fa-lock { color:rgb(118, 249, 118); } /* Verde pastel */
    .fa-info-circle { color:rgb(247, 200, 159); } /* Durazno pastel */
    .fa-eye-slash { color:rgb(213, 171, 213); } /* Lavanda pastel */
    .fa-heart { color:rgb(251, 158, 172); } /* Rosa pastel */
    .fa-search { color: #ADD8E6; } /* Azul pastel */
    .fa-venus-mars { color:rgb(126, 250, 126); } /* Verde pastel */
    .fa-image { color:rgb(247, 145, 55); } /* Durazno pastel */
    .fa-save { color: white; 
            margin-right: 10px;} /* Lavanda pastel */


</style>


<script>document.addEventListener('DOMContentLoaded', function() {
     const maxInterests = 10;
     const interestCheckboxes = document.querySelectorAll('input[name="interests[]"]');
     interestCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const selectedInterests = document.querySelectorAll('input[name="interests[]"]:checked').length;

            // Si el número de intereses seleccionados es mayor que el máximo permitido
            if (selectedInterests > maxInterests) {
                // Desmarcar el checkbox que acaba de ser seleccionado
                this.checked = false;
                alert(`Solo puedes seleccionar hasta ${maxInterests} intereses.`);
            }
        });
    });
    // Función de búsqueda de intereses
    document.getElementById('interestSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Lo que el usuario busca
        const allInterests = document.querySelectorAll('#interestList .form-check'); // Seleccionar los elementos correctos
        allInterests.forEach(function(interestDiv) {
            const label = interestDiv.querySelector('.form-check-label');
            let text = label.textContent.trim().toLowerCase();
            text = text.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Normalizar el texto del elemento
            if (text.includes(query)) {
                interestDiv.style.display = 'block'; // Mostrar el elemento
            } else {
                interestDiv.style.display = 'none'; // Ocultar el elemento
            }
        });
    });

    const profileNameInput = document.getElementById('nombre_perfil');
    const bioInput = document.getElementById('bio');
    const privacyInput = document.getElementById('privacy');
    const genderInput = document.getElementById('gender');
    const careerInput = document.getElementById('carrera');

    const previewProfileName = document.getElementById('previewProfileName').querySelector('.preview-placeholder');
    const previewBio = document.getElementById('previewBio').querySelector('.preview-placeholder');
    const previewCareer = document.getElementById('previewCareer').querySelector('.preview-placeholder');
    const previewPrivacy = document.getElementById('previewPrivacy').querySelector('.preview-placeholder');
    const previewInterests = document.getElementById('previewInterests').querySelector('.preview-placeholder');
    const previewGender = document.getElementById('previewGender').querySelector('.preview-placeholder');

    profileNameInput.addEventListener('input', function() {
    let nameValue = profileNameInput.value;
    // Permitir solo letras (mayúsculas y minúsculas, sin espacios ni caracteres especiales)
    nameValue = nameValue.replace(/[^a-zA-Z]/g, '');
    // Restringir la longitud mínima y máxima
    if (nameValue.length > 25) {
        nameValue = nameValue.slice(0, 25);
    }
    profileNameInput.value = nameValue;
    // Mostrar mensaje solo si tiene al menos 3 caracteres, de lo contrario, mensaje de error
    if (nameValue.length >= 3) {
        previewProfileName.textContent = nameValue;
    } else {
        previewProfileName.textContent = 'El nombre debe tener al menos 3 letras.';
    }
})


    bioInput.addEventListener('input', function() {
    const bioValue = bioInput.value;

    // Limitar el texto a los primeros 250 caracteres
    if (bioValue.length > 250) {
        bioInput.value = bioValue.slice(0, 250); // Cortar el texto si excede los 250 caracteres
    }

    // Limitar el texto en la previsualización a los primeros 250 caracteres
    previewBio.textContent = bioValue.length > 250 ? bioValue.slice(0, 250) + '...' : bioValue || 'Aún no has agregado una pequeña descripción de ti.';

    // Actualizar el contador de caracteres en tiempo real
    const bioCharCount = document.getElementById('bioCharCount');
    bioCharCount.textContent = `Máximo 250 caracteres - Quedan ${250 - bioValue.length} caracteres.`;
});

    // Actualizar la carrera en la previsualización
    careerInput.addEventListener('change', function() {
        previewCareer.textContent = careerInput.options[careerInput.selectedIndex].text || 'Aún no has seleccionado una carrera.';
    });
    // Actualizar privacidad en la previsualización
    privacyInput.addEventListener('change', function() {
        previewPrivacy.textContent = privacyInput.options[privacyInput.selectedIndex].text || 'Aún no has seleccionado una opción de privacidad.';
    });

    // Actualizar intereses en la previsualización
    const updateInterestsPreview = () => {
        const selectedInterests = Array.from(document.querySelectorAll('input[name="interests[]"]:checked'))
            .slice(0, 3) // Limitar a los primeros 3 intereses seleccionados
            .map(input => input.nextElementSibling.textContent)
            .join(', ');

        // Si hay más de 3 intereses seleccionados, mostrar "Más intereses"
        const moreInterests = document.querySelectorAll('input[name="interests[]"]:checked').length > 3;
        previewInterests.textContent = selectedInterests + (moreInterests ? '... (Más intereses)' : '') || 'Aún no has seleccionado tus intereses.';
    };

    // Llamamos a la función para actualizar la previsualización cuando se cargue la página
    updateInterestsPreview();

    // Actualizar los intereses en la previsualización cuando se seleccionan o deseleccionan
    document.querySelectorAll('input[name="interests[]"]').forEach(function(input) {
        input.addEventListener('change', updateInterestsPreview);
    });

    // Actualizar género en la previsualización
    genderInput.addEventListener('change', function() {
        previewGender.textContent = genderInput.options[genderInput.selectedIndex].text || 'Aún no has seleccionado un género.';
    });

    // Función para actualizar la previsualización de la imagen
    const avatarInput = document.getElementById('avatar');
    const previewContainer = document.getElementById('previewContainer');

    avatarInput.addEventListener('change', function() {
        // Si el usuario selecciona una imagen
        if (avatarInput.files && avatarInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.style.backgroundImage = `url(${e.target.result})`; // Usar la imagen seleccionada
            };
            reader.readAsDataURL(avatarInput.files[0]);
        } else {
            // Si no hay imagen seleccionada, usar la imagen por defecto
            previewContainer.style.backgroundImage = `url('{{ asset('storage/assets/default.webp') }}')`;
        }
    });

    // Validación del formulario antes de enviarlo
    document.getElementById('createProfileForm').addEventListener('submit', function(event) {
    let isValid = true;

    // Limpiar mensajes de error anteriores
    document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

    // Validar biografía
    const bio = document.getElementById('bio').value.trim();
    if (!bio) {
        isValid = false;
        showError('bio', 'La descripcion es obligatoria.');
    }

    // Validar privacidad
    const privacy = document.getElementById('privacy').value;
    if (!privacy) {
        isValid = false;
        showError('privacy', 'Debes seleccionar una opción de privacidad.');
    }

    // Validar género
    const gender = document.getElementById('gender').value;
    if (!gender) {
        isValid = false;
        showError('gender', 'Debes seleccionar un género.');
    }

    // Validar intereses
    const interests = document.querySelectorAll('input[name="interests[]"]:checked').length;
    if (interests === 0) {
        isValid = false;
        showError('interestList', 'Selecciona al menos un interés.');
    }

    // Prevenir envío si no es válido
    if (!isValid) {
        event.preventDefault();
    }
});

// Mostrar errores dinámicamente
function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const error = document.createElement('small');
    error.className = 'text-danger';
    error.textContent = message;

    if (field) {
        field.parentElement.appendChild(error);
    }
}
});

</script>

@endsection
