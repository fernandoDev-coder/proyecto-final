@extends('layouts.app')

@section('content')
<div class="movie-detail-container fade-in">
    <div class="movie-header">
        <div class="movie-poster">
            @if($pelicula->imagen)
                <img src="{{ url($pelicula->imagen) }}" 
                    alt="{{ $pelicula->titulo }}" 
                    class="img-fluid rounded shadow">
            @else
                <div class="no-poster">
                    <i class="fas fa-film fa-5x"></i>
                </div>
            @endif
        </div>

        <div class="movie-info">
            <h1 class="movie-title">{{ $pelicula->titulo }}</h1>
            
            <div class="movie-meta">
                <span class="duration">
                    <i class="fas fa-clock"></i> {{ $pelicula->duracion }} minutos
                </span>
                <span class="genre">
                    <i class="fas fa-tag"></i> {{ $pelicula->genero }}
                </span>
                <span class="rating">
                    <i class="fas fa-star"></i> {{ $pelicula->clasificacion }}
                </span>
            </div>

            <div class="movie-description">
                <h3>Sinopsis</h3>
                <p>{{ $pelicula->descripcion }}</p>
            </div>

            <div class="movie-actions">
                <a href="{{ route('peliculas.index') }}" 
                    class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Cartelera
                </a>
            </div>
        </div>
    </div>

    <div class="showtimes-section mt-4">
        <h2><i class="fas fa-calendar-alt"></i> Horarios Disponibles</h2>
        
        <div class="showtimes-grid">
            @forelse($pelicula->horarios as $horario)
                <div class="showtime-card card">
                    <div class="showtime-info">
                        <div class="date">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}
                        </div>
                        <div class="time">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::parse($horario->hora)->format('H:i') }}
                        </div>
                        <div class="sala">
                            <i class="fas fa-door-open"></i>
                            Sala {{ $horario->sala }}
                        </div>
                    </div>
                    <div class="showtime-actions">
                        <a href="{{ route('reservas.createDesdeHorario', $horario->id) }}" 
                            class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> Seleccionar
                        </a>
                    </div>
                </div>
            @empty
                <div class="no-showtimes text-center">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p>No hay horarios disponibles para esta película.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.movie-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.movie-header {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.movie-poster {
    position: relative;
    width: 100%;
    height: 450px;
    overflow: hidden;
    border-radius: 10px;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-poster {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--background-light);
    color: var(--text-gray);
}

.movie-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-family: var(--font-secondary);
}

.movie-meta {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    color: var(--text-gray);
}

.movie-description {
    margin-bottom: 2rem;
}

.movie-actions {
    display: flex;
    gap: 1rem;
}

.showtimes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.showtime-card {
    padding: 1.5rem;
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.showtime-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
    color: var(--text-light);
}

.showtime-info div {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.showtime-info i {
    color: var(--primary-color);
    width: 20px;
    text-align: center;
}

.showtime-actions {
    text-align: right;
}

.showtime-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-color);
}

@media (max-width: 768px) {
    .movie-header {
        grid-template-columns: 1fr;
    }

    .movie-poster {
        height: 300px;
    }
}
</style>
@endsection
