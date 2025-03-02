<!-- resources/views/auth/login.blade.php -->
 <!-- Formulario de inicio de sesión , de momento solo se llega aqui por
  la ruta , no tiene nada que ver con el modal , tener cuidado y no confundirlo
  borramos los estilos de este , al en teoria ya nadie poder entrar aqui-->
    <div class="container">
        <h2>Iniciar sesión</h2>
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control input-text-background" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control input-text-background" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>
        </form>

        <div class="mt-3">
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate aquí</a>
        </div>
    </div>
