@extends('layouts.app')

@section('content')
<div class="confirmation-container fade-in">
    <div class="confirmation-header text-center">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>¡Reserva Confirmada!</h1>
        <p class="text-gray">Tu reserva se ha realizado con éxito</p>
    </div>

    <div class="confirmation-content">
        <div class="card confirmation-card">
            <div class="card-header">
                <div class="d-flex gap-4">
                    <div class="movie-poster-small">
                        @if($reserva->horario->pelicula->imagen)
                            <img src="{{ asset('storage/' . $reserva->horario->pelicula->imagen) }}" 
                                alt="{{ $reserva->horario->pelicula->titulo }}" 
                                class="img-fluid rounded">
                        @else
                            <div class="no-poster">
                                <i class="fas fa-film fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="movie-info">
                        <h2>{{ $reserva->horario->pelicula->titulo }}</h2>
                        <div class="movie-meta">
                            <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($reserva->horario->fecha)->format('d/m/Y') }}</span>
                            <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($reserva->horario->hora)->format('H:i') }}</span>
                            <span><i class="fas fa-door-open"></i> Sala {{ $reserva->horario->sala }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="confirmation-details">
                    <div class="detail-row">
                        <span class="detail-label">Código de Reserva:</span>
                        <span class="detail-value code">{{ $reserva->codigo }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Asientos:</span>
                        <div class="seats-list">
                            @foreach(explode(',', $reserva->asientos) as $asiento)
                                <span class="seat-tag">{{ trim($asiento) }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Pagado:</span>
                        <span class="detail-value price">${{ number_format($reserva->total, 2) }}</span>
                    </div>
                </div>

                <div class="qr-section text-center mt-4">
                    <div class="qr-code" id="qrcode" data-codigo="{{ $reserva->codigo }}"></div>
                    <p class="text-gray mt-2">Muestra este código QR en la entrada del cine</p>
                </div>
            </div>

            <div class="card-footer">
                <div class="confirmation-actions">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimir Comprobante
                    </button>
                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-ticket-alt"></i> Ver Mis Reservas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.confirmation-container {
    max-width: 800px;
    margin: var(--spacing-xl) auto;
    padding: 0 var(--spacing-md);
}

.confirmation-header {
    margin-bottom: var(--spacing-xl);
}

.success-icon {
    font-size: 4rem;
    color: var(--accent-color);
    margin-bottom: var(--spacing-md);
    animation: scaleIn 0.5s ease-out;
}

.confirmation-card {
    overflow: hidden;
}

.card-header {
    background-color: var(--background-lighter);
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.movie-poster-small {
    width: 120px;
    height: 180px;
    overflow: hidden;
    border-radius: 5px;
}

.movie-poster-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.movie-info h2 {
    font-family: var(--font-secondary);
    margin-bottom: 1rem;
}

.movie-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    color: var(--text-gray);
}

.confirmation-details {
    padding: var(--spacing-md) 0;
}

.detail-row {
    display: flex;
    align-items: flex-start;
    padding: var(--spacing-md) 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-label {
    width: 150px;
    color: var(--text-gray);
}

.detail-value {
    flex-grow: 1;
}

.detail-value.code {
    font-family: monospace;
    font-size: 1.2rem;
    color: var(--accent-color);
}

.detail-value.price {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--accent-color);
}

.seats-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.seat-tag {
    background-color: var(--background-lighter);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

.qr-section {
    padding: var(--spacing-xl) 0;
}

.qr-code {
    display: inline-block;
    padding: var(--spacing-md);
    background-color: white;
    border-radius: 8px;
}

.card-footer {
    background-color: var(--background-lighter);
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.confirmation-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@media print {
    .navbar,
    .confirmation-actions,
    .btn {
        display: none !important;
    }

    body {
        background-color: white !important;
        color: black !important;
    }

    .confirmation-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .card-header,
    .card-footer {
        background-color: white !important;
    }

    .text-gray {
        color: #666 !important;
    }
}

@media (max-width: 768px) {
    .movie-info h2 {
        font-size: 1.5rem;
    }

    .confirmation-actions {
        flex-direction: column;
    }

    .detail-row {
        flex-direction: column;
    }

    .detail-label {
        width: 100%;
        margin-bottom: var(--spacing-sm);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qr = qrcode(0, 'M');
    qr.addData('{{ $reserva->codigo }}');
    qr.make();
    document.getElementById('qrcode').innerHTML = qr.createImgTag(5);
});
</script>
@endsection 