@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card user-profile-card">
                <div class="card-header">
                    <h2 class="text-center mb-0">
                        Mi Perfil
                        @if(Auth::user()->is_admin)
                            <i class="fas fa-star admin-star" title="Administrador"></i>
                        @endif
                    </h2>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="user-info">
                        <div class="profile-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        
                        <form action="{{ route('usuario.actualizar') }}" method="POST" class="user-form">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', Auth::user()->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', Auth::user()->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="password-section mt-4">
                                <h4>Cambiar Contraseña</h4>
                                <p class="text-light mb-3">Deja estos campos en blanco si no deseas cambiar tu contraseña</p>

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" 
                                           class="form-control @error('new_password') is-invalid @enderror" 
                                           id="new_password" 
                                           name="new_password">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="new_password_confirmation" 
                                           name="new_password_confirmation">
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>

                        @if(!Auth::user()->is_admin)
                            <div class="stats-container mt-4">
                                <div class="stat-item">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span class="stat-value">{{ Auth::user()->reservas->count() }}</span>
                                    <span class="stat-label">Reservas Realizadas</span>
                                </div>
                            </div>

                            <div class="actions mt-4">
                                <a href="{{ route('reservas.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list"></i> Ver Mis Reservas
                                </a>
                            </div>
                        @else
                            <div class="actions mt-4">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-chart-line"></i> Panel de Control
                                </a>
                                <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-film"></i> Gestionar Películas
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-profile-card {
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 15px;
    overflow: hidden;
    margin-top: 2rem;
}

.user-profile-card .card-header {
    background-color: var(--background-dark);
    border-bottom: 1px solid var(--border-color);
    color: white;
    padding: 1.5rem;
}

.admin-star {
    color: #ffd700;
    margin-left: 0.5rem;
    animation: star-pulse 2s infinite;
}

@keyframes star-pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.user-info {
    color: white;
    padding: 2rem;
}

.profile-icon {
    text-align: center;
    font-size: 5rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

.user-form {
    max-width: 500px;
    margin: 0 auto;
}

.form-control {
    background-color: var(--background-lighter);
    border: 1px solid var(--border-color);
    color: white;
}

.form-control:focus {
    background-color: var(--background-lighter);
    border-color: var(--primary-color);
    color: white;
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.25);
}

.form-control::placeholder {
    color: var(--text-gray);
}

.password-section {
    border-top: 1px solid var(--border-color);
    padding-top: 1.5rem;
}

.password-section h4 {
    color: white;
    margin-bottom: 0.5rem;
}

.stats-container {
    display: flex;
    justify-content: center;
    margin: 2rem 0;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background-color: var(--background-dark);
    border-radius: 10px;
    min-width: 200px;
}

.stat-item i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-value {
    display: block;
    font-size: 2rem;
    font-weight: bold;
    color: white;
}

.stat-label {
    display: block;
    color: var(--text-gray);
    font-size: 0.9rem;
}

.actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.actions .btn {
    padding: 0.8rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert {
    border-radius: 8px;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border-color: #28a745;
    color: #28a745;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection 