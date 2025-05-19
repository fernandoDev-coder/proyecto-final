@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="my-bookings-container fade-in">
    <header class="section-header text-center mb-4">
        <h1><i class="fas fa-ticket-alt"></i> Mis Reservas</h1>
        <p class="text-gray">Gestiona tus reservas de películas</p>
    </header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bookings-content">
        @if($reservas->isEmpty())
            <div class="empty-state text-center">
                <i class="fas fa-ticket-alt fa-4x mb-3"></i>
                <h3>No tienes reservas activas</h3>
                <p class="text-gray">¡Explora nuestro catálogo y reserva tu próxima película!</p>
                <a href="{{ route('peliculas.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-film"></i> Ver Cartelera
                </a>
            </div>
        @else
            <div class="bookings-grid">
                @foreach($reservas as $reserva)
                    <div class="booking-card card">
                        <div class="booking-header">
                            <div class="movie-poster">
                                @if($reserva->horario->pelicula->imagen)
                                    <img src="{{ $reserva->horario->pelicula->imagen }}" 
                                        alt="{{ $reserva->horario->pelicula->titulo }}"
                                        class="img-fluid">
                                @else
                                    <div class="no-poster">
                                        <i class="fas fa-film"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="booking-info">
                                <h3>{{ $reserva->horario->pelicula->titulo }}</h3>
                                <div class="booking-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($reserva->horario->fecha)->format('d/m/Y') }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($reserva->horario->hora)->format('H:i') }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-door-open"></i>
                                        Sala {{ $reserva->horario->sala }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="booking-details">
                            <div class="seats-info">
                                <h4><i class="fas fa-chair"></i> Asientos</h4>
                                <div class="seats-list">
                                    @foreach(explode(',', $reserva->asientos) as $asiento)
                                        <span class="seat-tag">{{ trim($asiento) }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="booking-code">
                                <h4><i class="fas fa-ticket-alt"></i> Código de Entrada</h4>
                                <div class="code">{{ $reserva->codigo_entrada }}</div>
                            </div>
                        </div>

                        <div class="booking-actions">
                            <button class="btn btn-primary" onclick="mostrarQR('{{ $reserva->id }}')">
                                <i class="fas fa-qrcode"></i> Ver QR
                            </button>
                            
                            @if(\Carbon\Carbon::parse($reserva->horario->fecha)->isFuture())
                                <form action="{{ route('reservas.destroy', $reserva->id) }}" 
                                    method="POST" 
                                    class="d-inline"
                                    onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-container mt-4">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal para QR -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title">Código QR de Reserva</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrcode"></div>
                <p class="mt-3 text-muted">Muestra este código QR en la entrada del cine</p>
            </div>
        </div>
    </div>
</div>

<style>
.my-bookings-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.bookings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.booking-card {
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.booking-card:hover {
    transform: translateY(-5px);
}

.booking-header {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.movie-poster {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.booking-info h3 {
    color: white;
    margin-bottom: 1rem;
}

.booking-meta {
    color: var(--text-gray);
}

.meta-item {
    margin-bottom: 0.5rem;
}

.meta-item i {
    width: 20px;
    color: var(--primary-color);
}

.booking-details {
    padding: 1rem;
}

.seats-info h4, .booking-code h4 {
    color: white;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.seats-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.seat-tag {
    background-color: var(--background-lighter);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

.booking-code .code {
    font-family: monospace;
    font-size: 1.2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.booking-actions {
    padding: 1rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--text-gray);
}

.empty-state i {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.alert {
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.2);
    color: #2ecc71;
}

.alert i {
    font-size: 1.2rem;
}

.btn-close {
    color: white;
}

@media (max-width: 768px) {
    .bookings-grid {
        grid-template-columns: 1fr;
    }
}

#qrcode {
    background-color: white;
    padding: 1rem;
    border-radius: 8px;
    display: inline-block;
}

#qrcode img {
    display: block;
    max-width: 100%;
    height: auto;
}
</style>

<script>
function mostrarQR(reservaId) {
    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    const qrcodeElement = document.getElementById('qrcode');
    
    // Limpiar el contenedor del QR
    qrcodeElement.innerHTML = '';
    
    // Hacer una petición AJAX para obtener el QR
    fetch(`/reservas/${reservaId}/qr`)
        .then(response => response.blob())
        .then(blob => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(blob);
            qrcodeElement.appendChild(img);
            modal.show();
        })
        .catch(error => {
            console.error('Error al generar el QR:', error);
            alert('Error al generar el código QR. Por favor, intenta de nuevo.');
        });
}
</script>
@endsection