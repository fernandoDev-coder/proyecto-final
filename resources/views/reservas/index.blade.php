@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="fas fa-ticket-alt"></i> Mis Reservas
            </h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($reservas->isEmpty())
                <div class="text-center empty-state">
                    <i class="fas fa-film fa-3x mb-3"></i>
                    <h3>No tienes reservas</h3>
                    <p>¡Explora nuestra cartelera y reserva tus entradas!</p>
                    <a href="{{ route('peliculas.index') }}" class="btn btn-primary">
                        <i class="fas fa-film"></i> Ver Cartelera
                    </a>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($reservas as $reserva)
                        <div class="col">
                            <div class="card h-100 bg-dark reservation-card">
                                <div class="card-img-top">
                                    @if($reserva->pelicula->imagen)
                                        <img src="{{ asset($reserva->pelicula->imagen) }}" 
                                             alt="{{ $reserva->pelicula->titulo }}" 
                                             class="img-fluid rounded-top">
                                    @else
                                        <div class="no-image-placeholder">
                                            <i class="fas fa-film fa-4x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $reserva->pelicula->titulo }}</h5>
                                    <div class="movie-info">
                                        <p class="mb-2">
                                            <i class="fas fa-calendar"></i>
                                            {{ $reserva->horario->fecha->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-clock"></i>
                                            {{ $reserva->horario->hora->format('H:i') }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-door-open"></i>
                                            Sala {{ $reserva->horario->sala }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-chair"></i>
                                            <strong>Asientos:</strong>
                                            {{ $reserva->cantidad_asientos }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-chair"></i>
                                            <strong>Números de asientos:</strong>
                                            {{ $reserva->asientos }}
                                        </p>
                                        @if($reserva->codigo_entrada)
                                            <p class="mb-1">
                                                <i class="fas fa-qrcode"></i>
                                                {{ $reserva->codigo_entrada }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex gap-2">
                                        <button type="button" 
                                                class="btn btn-primary flex-grow-1"
                                                onclick="mostrarQR({{ $reserva->id }}, '{{ $reserva->codigo_entrada }}')">
                                            <i class="fas fa-qrcode"></i> Ver QR
                                        </button>
                                        <form action="{{ route('reservas.destroy', $reserva->id) }}" 
                                            method="POST" 
                                            class="flex-grow-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para mostrar QR -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title" id="qrModalLabel">Código QR de Entrada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrContainer" class="p-3 bg-white rounded">
                    <!-- El QR se cargará aquí -->
                </div>
                <div class="mt-3">
                    <p class="mb-1">Código de entrada: <strong id="codigoEntrada"></strong></p>
                    <small class="text-white">Muestra este QR en la entrada del cine</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-color: var(--border-color);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    position: relative;
    height: 300px;
    overflow: hidden;
    background-color: var(--background-lighter);
}

.card-img-top img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: var(--background-lighter);
    padding: 10px;
}

.no-image-placeholder {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-gray);
    background-color: var(--background-lighter);
}

.reservation-card {
    border-radius: 10px;
    overflow: hidden;
}

.card-body {
    padding: 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.card-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: var(--text-light);
}

.movie-info {
    color: var(--text-gray);
    flex-grow: 1;
}

.movie-info p {
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.movie-info i {
    width: 20px;
    text-align: center;
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.card-footer {
    background-color: var(--background-lighter);
    border-top: 1px solid var(--border-color);
    padding: 1rem;
}

.btn {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    white-space: nowrap;
}

.btn i {
    margin-right: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    background-color: var(--background-lighter);
    border-radius: 10px;
    margin: 2rem 0;
}

.empty-state i {
    color: var(--text-gray);
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: var(--text-light);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-gray);
    margin-bottom: 1.5rem;
}

.alert {
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
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

.btn {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    color: white;
}

.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-info:hover {
    background-color: #138496;
    border-color: #117a8b;
    color: white;
}

.modal-content {
    border: 1px solid var(--border-color);
}

.modal-header {
    background-color: var(--background-lighter);
}

.modal-body {
    background-color: var(--background-dark);
}

#qrContainer {
    display: inline-block;
    margin: 0 auto;
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
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

    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function mostrarQR(reservaId, codigoEntrada) {
    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    const qrContainer = document.getElementById('qrContainer');
    const codigoElement = document.getElementById('codigoEntrada');
    
    // Mostrar el código de entrada
    codigoElement.textContent = codigoEntrada;
    
    // Limpiar el contenedor del QR
    qrContainer.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>';
    
    // Cargar el QR
    fetch(`/reservas/${reservaId}/qr`)
        .then(response => response.text())
        .then(svg => {
            qrContainer.innerHTML = svg;
        })
        .catch(error => {
            qrContainer.innerHTML = '<div class="alert alert-danger">Error al cargar el QR</div>';
            console.error('Error:', error);
        });
    
    // Mostrar el modal
    modal.show();
}
</script>
@endsection