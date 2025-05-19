@extends('layouts.app')

@section('content')
<div class="auth-container fade-in">
    <div class="auth-card">
        <h2 class="auth-title"><i class="fas fa-user-plus"></i> Registro</h2>

        @if(session('message'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Nombre</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                    name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Confirmar Contraseña</label>
                <input id="password-confirm" type="password" class="form-control" 
                    name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Registrarse
                </button>
            </div>

            <div class="auth-links text-center mt-3">
                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia Sesión</a></p>
            </div>
        </form>
    </div>
</div>

<style>
.auth-container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.auth-card {
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 2rem;
}

.auth-title {
    color: white;
    text-align: center;
    margin-bottom: 2rem;
}

.auth-form .form-group {
    margin-bottom: 1.5rem;
}

.auth-form label {
    color: white;
    margin-bottom: 0.5rem;
    display: block;
}

.auth-form .form-control {
    background-color: var(--background-dark);
    border: 1px solid var(--border-color);
    color: white;
    padding: 0.75rem;
}

.auth-form .form-control:focus {
    background-color: var(--background-dark);
    border-color: var(--primary-color);
    color: white;
    box-shadow: none;
}

.auth-form .btn-block {
    width: 100%;
    padding: 0.75rem;
    font-size: 1.1rem;
}

.auth-links {
    color: var(--text-light);
}

.auth-links a {
    color: var(--primary-color);
    text-decoration: none;
}

.auth-links a:hover {
    text-decoration: underline;
}

.alert {
    background-color: rgba(52, 152, 219, 0.1);
    border: 1px solid rgba(52, 152, 219, 0.2);
    color: #3498db;
    margin-bottom: 1.5rem;
}

.alert .btn-close {
    filter: invert(1);
}
</style>
@endsection