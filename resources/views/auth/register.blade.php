<!-- resources/views/auth/register.blade.php -->
 <!-- Formulario de registro , de momento solo se llega aqui por
  la ruta , no tiene nada que ver con el modal , tener cuidado y no confundirlo
  borramos los estilos de este , al en teoria ya nadie poder entrar aqui-->

    <div class="container">
        <h2>Crear una cuenta</h2>
        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nombre Completo</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>

        <div class="mt-3">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión aquí</a>
        </div>
    </div>
