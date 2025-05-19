@extends('layouts.app')

@section('content')
<div class="movies-container fade-in">
    <header class="section-header text-center mb-4">
        <h1><i class="fas fa-film"></i> Cartelera</h1>
        <p class="text-gray">Descubre las mejores películas y reserva tus entradas</p>
    </header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="search-section mb-4">
        <form action="{{ route('peliculas.index') }}" method="GET" class="search-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                            name="search" 
                            class="form-control search-input" 
                            placeholder="Buscar películas por título..." 
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="genero" class="form-select bg-dark text-white" onchange="this.form.submit()">
                        <option value="todos" {{ request('genero') == 'todos' ? 'selected' : '' }}>Todos los géneros</option>
                        @foreach($generos as $genero)
                            <option value="{{ $genero }}" {{ request('genero') == $genero ? 'selected' : '' }}>
                                {{ $genero }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="clasificacion" class="form-select bg-dark text-white" onchange="this.form.submit()">
                        <option value="todas" {{ request('clasificacion') == 'todas' ? 'selected' : '' }}>Todas las clasificaciones</option>
                        @foreach($clasificaciones as $clasificacion)
                            <option value="{{ $clasificacion }}" {{ request('clasificacion') == $clasificacion ? 'selected' : '' }}>
                                {{ $clasificacion }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    @if(!$peliculas->isEmpty())
        <div class="d-flex justify-content-center mt-4">
            {{ $peliculas->withQueryString()->links() }}
        </div>
    @endif

    @if($peliculas->isEmpty())
        <div class="text-center empty-state">
            <i class="fas fa-film fa-3x mb-3"></i>
            <h3>No se encontraron películas</h3>
            <p class="text-gray">Intenta con otros términos de búsqueda</p>
        </div>
    @else
        <div class="movies-grid">
            @foreach($peliculas as $pelicula)
                <div class="movie-card">
                    <a href="{{ route('peliculas.show', $pelicula->id) }}" class="movie-poster-link">
                        <div class="movie-poster">
                            @if($pelicula->imagen)
                                <img src="{{ url($pelicula->imagen) }}" 
                                    alt="{{ $pelicula->titulo }}" 
                                    class="movie-poster-img">
                            @else
                                <div class="no-poster">
                                    <i class="fas fa-film fa-3x"></i>
                                </div>
                            @endif
                            <div class="movie-overlay">
                                <span class="btn btn-primary btn-sm">
                                    <i class="fas fa-info-circle"></i> Más información
                                </span>
                            </div>
                        </div>
                    </a>
                    <div class="movie-info">
                        <h3 class="movie-title">{{ $pelicula->titulo }}</h3>
                        <div class="movie-meta">
                            <span class="duration">
                                <i class="fas fa-clock"></i> {{ $pelicula->duracion }} min
                            </span>
                            <span class="genre">
                                <i class="fas fa-tag"></i> {{ $pelicula->genero }}
                            </span>
                        </div>
                        <p class="movie-description">
                            {{ Str::limit($pelicula->descripcion, 100) }}
                        </p>
                        <div class="movie-actions">
                            @auth
                                @if(auth()->user()->is_admin)
                                    <form action="{{ route('admin.peliculas.destroy', $pelicula->id) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta película?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Eliminado rápido
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('peliculas.show', $pelicula->id) }}" 
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-ticket-alt"></i> Reservar
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.movies-container {
    max-width: 1200px;
    margin: 0 auto;
}

.section-header {
    margin-bottom: 2rem;
}

.search-section {
    max-width: 1200px;
    margin: 0 auto 2rem;
    padding: 1rem;
    background-color: var(--background-light);
    border-radius: 8px;
}

.search-input, .form-select {
    background-color: var(--background-lighter);
    border: 1px solid var(--border-color);
    color: var(--text-light);
}

.search-input::placeholder {
    color: var(--text-gray);
}

.form-select option {
    background-color: var(--background-lighter);
    color: var(--text-light);
}

.form-select:focus {
    background-color: var(--background-lighter);
    border-color: var(--primary-color);
    color: var(--text-light);
    box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
}

.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    padding: 1rem;
}

.movie-card {
    background-color: var(--background-light);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.movie-card:hover {
    transform: translateY(-5px);
}

.movie-poster-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.movie-poster {
    position: relative;
    padding-top: 150%;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.movie-poster-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.movie-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.movie-poster:hover .movie-overlay {
    opacity: 1;
}

.movie-info {
    padding: 1rem;
}

.movie-title {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: var(--text-light);
}

.movie-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-gray);
    margin-bottom: 0.5rem;
}

.movie-description {
    font-size: 0.875rem;
    color: var(--text-gray);
    margin-bottom: 1rem;
}

.movie-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.alert {
    border-radius: 8px;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border-color: #28a745;
    color: #28a745;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    border-color: #dc3545;
    color: #dc3545;
}

.pagination {
    --bs-pagination-color: #fff;
    --bs-pagination-bg: #343a40;
    --bs-pagination-border-color: #454d55;
    --bs-pagination-hover-color: #fff;
    --bs-pagination-hover-bg: #23272b;
    --bs-pagination-hover-border-color: #454d55;
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: #dc3545;
    --bs-pagination-active-border-color: #dc3545;
    --bs-pagination-disabled-color: #6c757d;
    --bs-pagination-disabled-bg: #343a40;
    --bs-pagination-disabled-border-color: #454d55;
    display: flex;
    justify-content: center;
    gap: 5px;
    margin-top: 20px;
}

.pagination > li {
    list-style: none;
}

.pagination .page-item .page-link {
    background-color: #343a40 !important;
    border: 1px solid #454d55 !important;
    color: #fff !important;
    padding: 8px 12px;
    border-radius: 4px;
}

.pagination .page-item.active .page-link {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: #fff !important;
}

.pagination .page-item .page-link:hover {
    background-color: #23272b !important;
    border-color: #454d55 !important;
    color: #fff !important;
}

.pagination .page-item.disabled .page-link {
    background-color: #343a40 !important;
    border-color: #454d55 !important;
    color: #6c757d !important;
    cursor: not-allowed;
}
</style>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
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