@extends('layouts.app')

@section('content')
<link rel="icon" href="{{ asset('storage/assets/icons/favicon.ico') }}?v=1" type="image/x-icon">

<link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <div class="row w-100">
            <div class="col-12 col-md-6 text-center mb-4">
            <img src="{{ asset('storage/assets/Logo.webp') }}" alt="Logo de la Red Social" class="img-logo" style="max-width: 75%;">
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
                <div class="text-center">
                <h2>Bienvenido a {{ config('globalvars.nombre_red_social') }}</h2>
                    <div class="mt-4">
                    <div class="btn-event" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <a href="#" class="link-succes-standar">Iniciar sesión</a>
                    </div>

                    </div>
                   <!-- Botón para abrir el modal de registro -->
                    <div class="mt-4">
                        <div class="btn-event" data-bs-toggle="modal" data-bs-target="#registerModal">
                            <a href="#" class="link-succes-standar">Registrarse</a>
                        </div>
                    </div>
                <!--Lineas para separar-->
                        <div class="mt-4 d-flex align-items-center">
                            <hr class="flex-grow-1" />
                            <span class="mx-3 text-muted">O</span>
                            <hr class="flex-grow-1" />
                        </div>
                   <!-- Botón para sesión con Google 
                   <div class="mt-3">
                    <a href="{{ route('login.google') }}" class="btn-event btn-google ">
                        <i class="fa-brands fa-google"></i>
                        <span>Iniciar sesión con Google</span>
                    </a>
                </div> -->
        <!-- Botón para sesión con Microsoft -->
        <div class="mt-3">
            <a href="{{ route('login.microsoft') }}" class="btn-event btn-microsoft d-flex align-items-center justify-content-center">
                <i class="fa-brands fa-microsoft me-2"></i>
                <span>Iniciar sesión con tu cuenta academica</span>
            </a>
        </div>

        <!-- Botón para registro con Microsoft -->
                
                </div>
                
            </div>
        </div>
    </div>


<!-- Modal Inicio de Sesión -->
 <div class="modal fade @if ($errors->any()) show @endif" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" style="@if ($errors->any()) display: block; @endif">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de inicio de sesión -->
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control input-class" 
                               placeholder="example@gmail.com" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control  input-class " 
                               placeholder="contraseña" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-event width-btn-100">Iniciar sesión</button>
                    </div>
                </form>

                <div class="mt-3 text-center acciones-modal">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Olvidaste tu contraseña?') }}
                    </a>
                @endif
                    <p>¿No tienes cuenta? <a href="" data-bs-toggle="modal" data-bs-target="#registerModal"> Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Recuperación de Contraseña -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de recuperación de contraseña -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">{{ __('Dirección de correo electrónico') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Enviar enlace para restablecer contraseña') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Registro -->
<div class="modal fade @if (session('registerModal') || ($errors->any() && old('register'))) show @endif" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true" style="@if (session('registerModal') || ($errors->any() && old('register'))) display: block; @endif">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Crear una cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de registro -->
                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <input type="hidden" name="register" value="true">

                    <div class="form-group">
                        <label for="name">Nombre para tu perfil</label>
                        <input type="text" name="name" id="name" class="form-control input-border" placeholder="Alexander" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control input-border" 
                               placeholder="example@gmail.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control input-border" 
                               placeholder="contraseña segura" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control input-border" placeholder="contraseña segura "required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-event width-btn-100">Registrar</button>
                    </div>
                </form>

                <div class="mt-3 text-center acciones-modal">
                    <p>Ya tienes una cuenta <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const loginForm = document.querySelector("#loginModal form");
        const registerForm = document.querySelector("#registerModal form");

        const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        const isValidName = (name) => /^[a-zA-Z\s]{3,20}$/.test(name);

        const showError = (input, message) => {
            const errorElement = input.parentElement.querySelector(".text-danger");
            if (errorElement) errorElement.remove();
            const error = document.createElement("div");
            error.className = "text-danger mt-1";
            error.textContent = message;
            input.parentElement.appendChild(error);
        };

        const clearError = (input) => {
            const errorElement = input.parentElement.querySelector(".text-danger");
            if (errorElement) errorElement.remove();
        };

        // Validar el formulario de registro
        registerForm.addEventListener("submit", (e) => {
            let hasErrors = false;

            // Validar el nombre
            const name = registerForm.querySelector("#name");
            if (!isValidName(name.value.trim())) {
                showError(name, "El nombre debe contener solo letras y entre 3 y 20 caracteres.");
                hasErrors = true;
            } else {
                clearError(name);
            }

            // Validar el correo
            const email = registerForm.querySelector("#email");
            if (!isValidEmail(email.value.trim())) {
                showError(email, "Por favor ingresa un correo válido.");
                hasErrors = true;
            } else {
                clearError(email);
            }

            // Validar la contraseña
            const password = registerForm.querySelector("#password");
            if (password.value.length < 6) {
                showError(password, "La contraseña debe tener al menos 6 caracteres.");
                hasErrors = true;
            } else {
                clearError(password);
            }

            // Validar la confirmación de la contraseña
            const passwordConfirmation = registerForm.querySelector("#password_confirmation");
            if (password.value !== passwordConfirmation.value) {
                showError(passwordConfirmation, "Las contraseñas no coinciden.");
                hasErrors = true;
            } else {
                clearError(passwordConfirmation);
            }

            // Prevenir el envío si hay errores
            if (hasErrors) e.preventDefault();
        });

        // Validar el formulario de inicio de sesión
        loginForm.addEventListener("submit", (e) => {
            let hasErrors = false;

            // Validar el correo
            const email = loginForm.querySelector("#email");
            if (!isValidEmail(email.value.trim())) {
                showError(email, "Por favor ingresa un correo válido.");
                hasErrors = true;
            } else {
                clearError(email);
            }

            // Validar la contraseña
            const password = loginForm.querySelector("#password");
            if (password.value.length < 6) {
                showError(password, "La contraseña debe tener al menos 6 caracteres.");
                hasErrors = true;
            } else {
                clearError(password);
            }

            // Prevenir el envío si hay errores
            if (hasErrors) e.preventDefault();
        });

        // Limpiar los inputs al cerrar los modales
        const clearModalInputs = (form) => {
            form.reset(); // Limpia todos los inputs del formulario
            form.querySelectorAll(".text-danger").forEach((error) => error.remove()); // Remueve mensajes de error
        };

        // Evento para limpiar inputs al cerrar el modal de inicio de sesión
        document.querySelector("#loginModal").addEventListener("hidden.bs.modal", () => {
            clearModalInputs(loginForm);
        });

        // Evento para limpiar inputs al cerrar el modal de registro
        document.querySelector("#registerModal").addEventListener("hidden.bs.modal", () => {
            clearModalInputs(registerForm);
        });
    });
</script>

@endsection
