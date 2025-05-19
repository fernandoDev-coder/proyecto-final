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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="user-info">
                        <div class="profile-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        
                        <div class="user-details">
                            <h3>{{ Auth::user()->name }}</h3>
                            <p class="email">{{ Auth::user()->email }}</p>
                            
                            @if(Auth::user()->is_admin)
                                <div class="admin-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    Administrador del Sistema
                                </div>
                            @endif
                        </div>

                        <div class="stats-container mt-4">
                            @if(!Auth::user()->is_admin)
                                <div class="stat-item">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span class="stat-value">{{ Auth::user()->reservas->count() }}</span>
                                    <span class="stat-label">Reservas Realizadas</span>
                                </div>
                            @endif
                        </div>

                        <div class="actions mt-4">
                            @if(!Auth::user()->is_admin)
                                <a href="{{ route('reservas.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list"></i> Mis Reservas
                                </a>
                            @endif
                        </div>
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
    text-align: center;
    color: white;
    padding: 2rem;
}

.profile-icon {
    font-size: 5rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

.user-details h3 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: white;
}

.email {
    color: var(--text-light);
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.admin-badge {
    display: inline-block;
    background-color: rgba(255, 215, 0, 0.1);
    color: #ffd700;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    margin: 1rem 0;
}

.admin-badge i {
    margin-right: 0.5rem;
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
    color: var(--text-light);
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
</style>
@endsection
