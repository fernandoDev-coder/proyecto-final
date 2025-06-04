@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header">
                    <h2 class="mb-0">Detalles de la Reserva</h2>
                </div>

                <div class="card-body">
                    <div class="movie-info mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                @if($reserva->pelicula->imagen)
                                    <img src="{{ asset($reserva->pelicula->imagen) }}" 
                                         alt="{{ $reserva->pelicula->titulo }}" 
                                         class="img-fluid rounded">
                                @else
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-film fa-4x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h3>{{ $reserva->pelicula->titulo }}</h3>
                                <div class="status-badge mb-3">
                                    <span class="badge {{ $reserva->estado === 'confirmada' ? 'bg-success' : ($reserva->estado === 'pendiente' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </div>
                                <div class="reservation-details">
                                    <p class="mb-2">
                                        <i class="fas fa-calendar"></i>
                                        <strong>Fecha:</strong> 
                                        {{ $reserva->horario->fecha->format('d/m/Y') }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-clock"></i>
                                        <strong>Hora:</strong>
                                        {{ $reserva->horario->hora->format('H:i') }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-door-open"></i>
                                        <strong>Sala:</strong>
                                        {{ $reserva->horario->sala }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-chair"></i>
                                        <strong>Asientos:</strong>
                                        {{ $reserva->cantidad_asientos }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-chair"></i>
                                        <strong>Números de asientos:</strong>
                                        <span class="badge bg-primary">{{ $reserva->asientos }}</span>
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-clock"></i>
                                        <strong>Reservado el:</strong>
                                        {{ $reserva->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="actions mt-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('reservas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                <span>Volver a Mis Reservas</span>
                            </a>
                            @if($reserva->estado === 'pendiente' || $reserva->estado === 'confirmado')
                                <form action="{{ route('reservas.destroy', $reserva) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i>
                                        <span>Cancelar Reserva</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-color: var(--border-color);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: var(--background-lighter);
    border-bottom-color: var(--border-color);
}

.no-image-placeholder {
    background-color: var(--background-lighter);
    border-radius: 8px;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-gray);
}

.movie-info {
    background-color: var(--background-lighter);
    padding: 1.5rem;
    border-radius: 8px;
}

.reservation-details {
    color: var(--text-gray);
}

.reservation-details i {
    width: 20px;
    text-align: center;
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.reservation-details strong {
    color: var(--text-light);
    margin-right: 0.5rem;
}

.badge {
    padding: 0.5em 0.75em;
    font-size: 0.9em;
}

.status-badge {
    font-size: 1.1em;
}

.btn i {
    margin-right: 0.5rem;
}
</style>
@endsection
