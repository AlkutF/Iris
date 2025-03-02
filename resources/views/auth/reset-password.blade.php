<!-- resources/views/auth/reset-password.blade.php -->

@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
<div class="container justify-content-center align-items-center vh-100">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6" style="max-width: 50vw;">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-primary text-white">
                    <h4>{{ __('Restablecer tu contraseña') }}</h4>
                </div>

                <div class="card-body p-4">
                    <!-- Mostrar mensajes de estado o error -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Campo token oculto -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Campo de email (solo lectura) -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Correo electrónico') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $request->email }}" readonly>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Campo de nueva contraseña -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Nueva contraseña') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Campo de confirmar contraseña -->
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar contraseña') }}</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <!-- Botón para restablecer contraseña -->
                        <div class="form-group">
                            <button type="submit" class="btn-event width-btn-100">
                                {{ __('Restablecer contraseña') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
