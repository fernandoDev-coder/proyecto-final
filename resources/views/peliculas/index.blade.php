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
        <form action="{{ route('peliculas.index') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control bg-dark text-white" 
                               placeholder="Buscar películas por título..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="clasificacion" class="form-select bg-dark text-white" id="clasificacionSelect">
                        <option value="todas">Todas las clasificaciones</option>
                        @foreach(['G', 'PG', 'PG-13', 'R', 'NC-17'] as $clasificacion)
                            <option value="{{ $clasificacion }}" {{ request('clasificacion') == $clasificacion ? 'selected' : '' }}>
                                {{ $clasificacion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="genero" class="form-select bg-dark text-white" id="generoSelect">
                        <option value="">Todos los géneros</option>
                        @foreach($generos as $genero)
                            <option value="{{ $genero->id }}" {{ request('genero') == $genero->id ? 'selected' : '' }}>
                                {{ $genero->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(!empty(request('generos')))
                <div class="selected-genres mt-2">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($generos->whereIn('id', (array)request('generos')) as $genero)
                            <span class="badge bg-primary">
                                {{ $genero->nombre }}
                                <a href="javascript:void(0)" 
                                   onclick="removeGenre('{{ $genero->id }}')" 
                                   class="text-white text-decoration-none ms-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endforeach
                        <a href="{{ route('peliculas.index', array_merge(
                            request()->except('generos'),
                            ['page' => 1]
                        )) }}" 
                           class="badge bg-danger text-decoration-none">
                            Limpiar filtros <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            @endif
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
                                <img src="{{ asset($pelicula->imagen) }}" 
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
                            <span class="duration" title="Duración">
                                <i class="fas fa-clock"></i> 
                                @php
                                    $horas = floor($pelicula->duracion / 60);
                                    $minutos = $pelicula->duracion % 60;
                                    $duracionFormateada = $horas > 0 ? $horas . 'h ' : '';
                                    $duracionFormateada .= $minutos . 'min';
                                @endphp
                                {{ $duracionFormateada }}
                            </span>
                            <span class="genres" title="Géneros">
                                <i class="fas fa-tag"></i>
                                {{ $pelicula->generos->pluck('nombre')->join(', ') }}
                            </span>
                            <span class="classification-badge" title="Clasificación">
                                <i class="fas fa-star"></i> {{ $pelicula->clasificacion }}
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
                                    <a href="{{ route('reservas.create', $pelicula->id) }}" 
                                       class="btn btn-primary d-inline-flex align-items-center justify-content-center">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span>Reservar</span>
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
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.search-input, .form-select {
    background-color: var(--background-lighter);
    border: 1px solid var(--border-color);
    color: var(--text-light);
    transition: all 0.3s ease;
}

.search-input:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.25);
}

.search-input::placeholder {
    color: var(--text-gray);
}

.form-select option {
    background-color: var(--background-lighter);
    color: var(--text-light);
}

.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    padding: 1rem;
}

.movie-card {
    background-color: var(--background-light);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.movie-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
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
}

.movie-poster-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.movie-card:hover .movie-poster-img {
    transform: scale(1.05);
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
    padding: 1.5rem;
}

.movie-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: var(--text-light);
    font-weight: 600;
}

.movie-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: var(--text-gray);
}

.movie-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.classification-badge {
    background-color: var(--primary-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    position: relative;
    cursor: help;
}

.classification-badge .tooltip-text {
    visibility: hidden;
    background-color: rgba(0, 0, 0, 0.9);
    color: #fff;
    text-align: center;
    padding: 5px 10px;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    font-size: 0.75rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.classification-badge .tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: rgba(0, 0, 0, 0.9) transparent transparent transparent;
}

.classification-badge:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

.movie-description {
    font-size: 0.875rem;
    color: var(--text-gray);
    margin-bottom: 1rem;
    line-height: 1.5;
}

.movie-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--text-gray);
}

.empty-state i {
    margin-bottom: 1rem;
    color: var(--text-gray);
}

@media (max-width: 768px) {
    .movies-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .movie-info {
        padding: 1rem;
    }

    .movie-title {
        font-size: 1.1rem;
    }

    .movie-meta {
        font-size: 0.8rem;
    }
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

.form-select[multiple] {
    height: auto;
    min-height: 38px;
    padding: 0;
}

.form-select[multiple] option {
    padding: 8px 12px;
    margin: 0;
    border-bottom: 1px solid var(--border-color);
}

.form-select[multiple] option:last-child {
    border-bottom: none;
}

.form-select[multiple] option:hover {
    background-color: var(--primary-color);
    color: white;
}

.form-select[multiple] option:checked {
    background-color: var(--primary-color);
    color: white;
}

.selected-genres {
    margin-top: 1rem;
}

.selected-genres .badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    background-color: var(--primary-color);
}

.selected-genres .badge a {
    opacity: 0.8;
    transition: opacity 0.2s;
}

.selected-genres .badge a:hover {
    opacity: 1;
}

#generosSelect {
    height: auto;
    min-height: 38px;
}

#generosSelect option {
    padding: 0.5rem;
}

.form-select {
    background-color: var(--background-lighter);
    border: 1px solid var(--border-color);
    color: var(--text-light);
    transition: all 0.3s ease;
    padding: 8px 12px;
}

.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(229, 9, 20, 0.25);
}

.form-select option {
    padding: 8px 12px;
    background-color: var(--background-lighter);
    color: var(--text-light);
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

document.addEventListener('DOMContentLoaded', function() {
    // Actualizar automáticamente al cambiar la clasificación
    document.getElementById('clasificacionSelect').addEventListener('change', function() {
        this.form.submit();
    });

    // Actualizar automáticamente al cambiar el género
    document.getElementById('generoSelect').addEventListener('change', function() {
        this.form.submit();
    });
});

function removeGenre(generoId) {
    const select = document.getElementById('generosSelect');
    
    // Deseleccionar el género específico
    Array.from(select.options).forEach(option => {
        if (option.value === generoId) {
            option.selected = false;
        }
    });
    
    // Enviar el formulario
    select.form.submit();
}
</script>
@endsection