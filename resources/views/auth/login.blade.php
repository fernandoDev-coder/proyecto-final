@extends('layouts.app')

@section('content')
<div class="auth-container fade-in">
    <div class="auth-card">
        <h2 class="auth-title"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h2>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                    name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" name="remember" 
                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Recordarme
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </div>

            <div class="auth-links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
                <a href="{{ route('register') }}">
                    ¿No tienes cuenta? Regístrate
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.auth-container {
    max-width: 400px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.auth-card {
    background-color: var(--background-light);
    border-radius: 8px;
    padding: 2rem;
    box-shadow: var(--shadow-lg);
}

.auth-title {
    color: var(--text-light);
    text-align: center;
    margin-bottom: 2rem;
    font-family: var(--font-secondary);
}

.auth-form .form-group {
    margin-bottom: 1.5rem;
}

.auth-form label {
    color: var(--text-light);
    margin-bottom: 0.5rem;
    display: block;
}

.auth-form .form-control {
    background-color: var(--background-lighter);
    border: 1px solid var(--border-color);
    color: var(--text-light);
    padding: 0.75rem;
}

.auth-form .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: none;
}

.auth-form .form-check-label {
    color: var(--text-gray);
}

.btn-block {
    width: 100%;
    padding: 0.75rem;
    font-size: 1.1rem;
}

.auth-links {
    margin-top: 1.5rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.auth-links a {
    color: var(--text-gray);
    text-decoration: none;
    transition: color 0.3s ease;
}

.auth-links a:hover {
    color: var(--primary-color);
}

.invalid-feedback {
    color: var(--primary-color);
}
</style>
@endsection