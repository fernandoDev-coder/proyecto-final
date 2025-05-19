@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card auth-card">
                <div class="card-header text-center">Restablecer Contraseña</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-3">
                            <label for="email">Correo Electrónico</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Nueva Contraseña</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password-confirm">Confirmar Contraseña</label>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group mb-0 text-center">
                            <button type="submit" class="btn btn-primary btn-block">
                                Restablecer Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-card {
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    margin-top: 2rem;
}

.auth-card .card-header {
    background-color: transparent;
    border-bottom: 1px solid var(--border-color);
    color: white;
    font-size: 1.5rem;
    padding: 1rem;
}

.auth-form {
    padding: 1rem;
}

.auth-form label {
    color: white;
    margin-bottom: 0.5rem;
}

.auth-form .form-control {
    background-color: var(--background-dark);
    border: 1px solid var(--border-color);
    color: white;
}

.auth-form .form-control:focus {
    background-color: var(--background-dark);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    color: white;
}

.auth-form .btn-block {
    width: 100%;
    padding: 0.8rem;
    font-size: 1.1rem;
    margin-top: 1rem;
}
</style>
@endsection