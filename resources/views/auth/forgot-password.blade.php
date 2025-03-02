<!-- resources/views/auth/forgot-password.blade.php -->

@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
<div class="container min-vh-100 d-flex align-items-center">
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">{{ __('¿Olvidaste tu contraseña?') }}</div>

                <div class="card-body">
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
                            <button type="submit" class="btn-event width-btn-100">
                                {{ __('Enviar enlace para restablecer contraseña') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
