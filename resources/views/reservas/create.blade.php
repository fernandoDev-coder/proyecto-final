@extends('layouts.app')

@section('content')
<div class="booking-container fade-in">
    <div class="booking-header text-center mb-4">
        <h1><i class="fas fa-ticket-alt"></i> Reserva de Entradas</h1>
        <p class="movie-title">{{ $pelicula->titulo }}</p>
    </div>

    <div class="booking-content">
        <div class="movie-summary card mb-4">
            <div class="card-body">
                <div class="d-flex gap-4">
                    <div class="movie-poster-small">
                        @if($pelicula->imagen)
                            <img src="{{ $pelicula->imagen }}" 
                                alt="{{ $pelicula->titulo }}" 
                                class="img-fluid rounded">
                        @else
                            <div class="no-poster">
                                <i class="fas fa-film fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="movie-info">
                        <h3>{{ $pelicula->titulo }}</h3>
                        <div class="movie-meta">
                            <span><i class="fas fa-clock"></i> {{ $pelicula->duracion }} min</span>
                            <span><i class="fas fa-tag"></i> {{ $pelicula->genero }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('reservas.storeDesdeHorario', $horario) }}" method="POST" class="booking-form" id="reservaForm">
            @csrf
            <input type="hidden" name="pelicula_id" value="{{ $pelicula->id }}">
            <input type="hidden" name="horario_id" value="{{ $horario->id }}">
            
            <div class="form-section card mb-4">
                <div class="card-body">
                    <h3><i class="fas fa-calendar-alt"></i> Horario Seleccionado</h3>
                    
                    <div class="selected-showtime mt-3">
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
                    </div>
                </div>
            </div>

            <div class="form-section card mb-4">
                <div class="card-body">
                    <h3><i class="fas fa-chair"></i> Selecciona tus Asientos</h3>
                    
                    <div class="seats-container mt-3">
                        <div class="screen mb-4">
                            <div class="screen-label">PANTALLA</div>
                        </div>
                        
                        <div class="seats-grid">
                            @php
                                // Generar algunos asientos ocupados aleatoriamente
                                $asientosOcupados = [];
                                $numAsientosOcupados = rand(5, 10); // Entre 5 y 10 asientos ocupados
                                
                                while (count($asientosOcupados) < $numAsientosOcupados) {
                                    $fila = rand(1, 8);
                                    $columna = rand(1, 10);
                                    $asiento = chr(64 + $fila) . $columna;
                                    $asientosOcupados[$asiento] = true;
                                }
                            @endphp

                            @for($fila = 1; $fila <= 8; $fila++)
                                <div class="seat-row">
                                    <div class="seat-letter">{{ chr(64 + $fila) }}</div>
                                    @for($columna = 1; $columna <= 10; $columna++)
                                        @php
                                            $asientoId = chr(64 + $fila) . $columna;
                                            $estaOcupado = isset($asientosOcupados[$asientoId]);
                                        @endphp
                                        <div class="seat-wrapper">
                                            <input type="checkbox" 
                                                name="asientos[]" 
                                                id="asiento_{{ $fila }}_{{ $columna }}" 
                                                value="{{ $asientoId }}"
                                                class="seat-checkbox"
                                                {{ $estaOcupado ? 'disabled' : '' }}>
                                            <label for="asiento_{{ $fila }}_{{ $columna }}" 
                                                class="seat {{ $estaOcupado ? 'occupied' : '' }}">
                                                <i class="fas fa-chair"></i>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            @endfor
                        </div>

                        <div class="seats-legend mt-4">
                            <div class="legend-item">
                                <div class="seat-example available">
                                    <i class="fas fa-chair"></i>
                                </div>
                                <span>Disponible</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-example selected">
                                    <i class="fas fa-chair"></i>
                                </div>
                                <span>Seleccionado</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-example occupied">
                                    <i class="fas fa-chair"></i>
                                </div>
                                <span>Ocupado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-summary card mb-4">
                <div class="card-body">
                    <h3><i class="fas fa-receipt"></i> Resumen de la Reserva</h3>
                    
                    <div class="summary-details mt-3">
                        <div class="summary-row">
                            <span>Película:</span>
                            <span>{{ $pelicula->titulo }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Asientos seleccionados:</span>
                            <span id="selected-seats">-</span>
                        </div>
                        <div class="summary-row">
                            <span>Precio por asiento:</span>
                            <span>8,00 €</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="total-price">0,00 €</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nueva sección de pago -->
            <div class="payment-section card mb-4">
                <div class="card-body">
                    <h3><i class="fas fa-credit-card"></i> Método de Pago</h3>
                    
                    <div class="credit-card-display mt-3">
                        <div class="credit-card">
                            <div class="credit-card-header">
                                <div class="chip"></div>
                                <i class="fab fa-cc-visa card-logo"></i>
                            </div>
                            <div class="credit-card-body">
                                <div class="card-number">4532 9856 **** ****</div>
                                <div class="card-info">
                                    <div class="card-holder">
                                        <span class="label">Titular</span>
                                        <span class="value">USUARIO DEMO</span>
                                    </div>
                                    <div class="card-expiry">
                                        <span class="label">Válida hasta</span>
                                        <span class="value">12/25</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment-verified">
                            <i class="fas fa-check-circle"></i>
                            <span>Pago verificado</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-actions text-center">
                <button type="submit" class="btn btn-primary btn-lg" id="btnConfirmar">
                    <i class="fas fa-check-circle"></i> Confirmar Reserva
                </button>
                <a href="{{ route('peliculas.show', $pelicula) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <!-- Mensaje de error para asientos no seleccionados -->
            <div class="alert alert-danger mt-3" id="errorAsientos" style="display: none;">
                <i class="fas fa-exclamation-circle"></i> Por favor, selecciona al menos un asiento antes de confirmar.
            </div>
        </form>
    </div>
    </div>

    <style>
.booking-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
    color: white;
}

.movie-poster-small {
    width: 120px;
    height: 180px;
    overflow: hidden;
}

.movie-poster-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.movie-info h3 {
    color: white;
    margin-bottom: 1rem;
}

.movie-meta {
    color: var(--text-light);
}

.movie-meta span {
    margin-right: 1.5rem;
}

.card {
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
}

.card-body {
    color: white;
}

.card-body h3 {
    color: white;
    margin-bottom: 1.5rem;
}

.showtimes-grid {
            display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.showtime-option input[type="radio"] {
    display: none;
}

.showtime-option input[type="radio"]:checked + label .showtime-card {
    border-color: var(--primary-color);
    background-color: rgba(52, 152, 219, 0.1);
}

.showtime-card {
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: var(--background-dark);
    padding: 0.5rem;
    margin: 0.5rem;
    border-radius: 8px;
}

.showtime-info {
    color: white;
    padding: 1rem;
}

.showtime-info div {
    margin-bottom: 0.8rem;
    font-size: 1.1rem;
}

.showtime-info .date {
    font-weight: bold;
    color: #fff;
}

.showtime-info .time {
    color: #e0e0e0;
}

.showtime-info .sala {
    color: #cccccc;
}

.screen-label {
    position: absolute;
    width: 100%;
    text-align: center;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.seat-letter {
    color: white;
}

.seats-legend span {
    color: white;
}

.summary-details {
    color: white;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.summary-row.total {
    border-top: 2px solid rgba(255, 255, 255, 0.2);
    border-bottom: none;
    font-weight: bold;
    font-size: 1.2rem;
    margin-top: 1rem;
    padding-top: 1rem;
}

.text-gray {
    color: var(--text-light) !important;
}

.booking-header h1 {
    color: white;
    margin-bottom: 0.5rem;
}

.showtime-card:hover {
    transform: translateY(-2px);
}

.screen {
    background: linear-gradient(to bottom, #ffffff22, transparent);
    height: 50px;
    position: relative;
    border-radius: 50% 50% 0 0;
}

.seats-grid {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}

.seat-row {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.seat-wrapper {
    position: relative;
}

.seat-checkbox {
    display: none;
        }

        .seat {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
            cursor: pointer;
    transition: all 0.3s ease;
    color: var(--text-gray);
    position: relative;
}

.seat i {
    font-size: 1.4rem;
    transition: all 0.3s ease;
}

.seat:hover:not(.occupied) {
    transform: scale(1.1);
    color: #ffa500; /* Color naranja para hover */
}

.seat-checkbox:checked + .seat {
    color: #ffa500; /* Color naranja para seleccionado */
    transform: scale(1.1);
        }

        .seat.occupied {
    color: #ff4444; /* Color rojo para ocupado */
            cursor: not-allowed;
    opacity: 0.8;
}

.seat.occupied:hover {
    transform: none;
}

.seat-example {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.seat-example.available {
    color: var(--text-gray);
}

.seat-example.selected {
    color: #ffa500; /* Color naranja para ejemplo de seleccionado */
}

.seat-example.occupied {
    color: #ff4444; /* Color rojo para ejemplo de ocupado */
}

.seats-legend {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 2rem;
    padding: 1rem;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.5rem 1rem;
}

/* Añadir efecto de hover suave para los asientos disponibles */
.seat:not(.occupied)::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 165, 0, 0.1);
    border-radius: 4px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.seat:not(.occupied):hover::after {
    opacity: 1;
}

/* Efecto de pulso para asientos seleccionados */
@keyframes seatPulse {
    0% { transform: scale(1.1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1.1); }
}

.seat-checkbox:checked + .seat {
    animation: seatPulse 2s infinite;
}

.booking-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.movie-title {
    font-size: 2.5rem;
    color: #ff4444;
    margin: 1rem 0;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    .seats-grid {
        transform: scale(0.8);
        transform-origin: center top;
    }
}

.selected-showtime {
    background-color: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
}

.selected-showtime .showtime-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
}

.selected-showtime .showtime-info div {
    font-size: 1.2rem;
            color: white;
}

.selected-showtime .showtime-info i {
    color: var(--primary-color);
    margin-right: 0.8rem;
    width: 20px;
    text-align: center;
}

.selected-showtime .date {
    font-weight: bold;
    color: #fff !important;
}

.selected-showtime .time {
    color: #e0e0e0 !important;
}

.selected-showtime .sala {
    color: #cccccc !important;
}

.alert {
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.2);
    color: #ff6b6b;
}

.alert i {
    font-size: 1.2rem;
}

/* Efecto de shake para el mensaje de error */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.shake {
    animation: shake 0.8s cubic-bezier(.36,.07,.19,.97) both;
}

.btn-primary {
    background: linear-gradient(45deg, #dc3545 0%, #e35d6a 100%);
    border: none;
    padding: 1rem 2rem;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

/* Estilos para la sección de pago */
.payment-section {
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
}

.credit-card-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.credit-card {
    width: 340px;
    height: 200px;
    background: linear-gradient(135deg, #434343 0%, #000000 100%);
    border-radius: 15px;
    padding: 20px;
    color: white;
    position: relative;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.credit-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chip {
    width: 45px;
    height: 35px;
    background: linear-gradient(135deg, #ffd700 0%, #b8860b 100%);
    border-radius: 5px;
    position: relative;
}

.chip::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 35px;
    height: 25px;
    background: linear-gradient(135deg, #ffd700 0%, #b8860b 100%);
    border-radius: 3px;
    border: 1px solid rgba(0, 0, 0, 0.3);
}

.card-logo {
    font-size: 30px;
    color: white;
}

.card-number {
    font-family: monospace;
    font-size: 1.5rem;
    letter-spacing: 3px;
    text-align: center;
    margin: 20px 0;
}

.card-info {
    display: flex;
    justify-content: space-between;
}

.card-holder, .card-expiry {
    display: flex;
    flex-direction: column;
}

.label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.7);
}

.value {
    font-size: 0.9rem;
    letter-spacing: 1px;
}

.payment-verified {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #2ecc71;
    font-size: 0.9rem;
}

.payment-verified i {
    font-size: 1.2rem;
}

/* Animación suave para la tarjeta */
.credit-card {
    transition: transform 0.3s ease;
}

.credit-card:hover {
    transform: scale(1.02);
}
    </style>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Constantes para elementos del DOM
    const asientos = document.querySelectorAll('.seat-checkbox');
    const selectedSeatsElement = document.getElementById('selected-seats');
    const totalPriceElement = document.getElementById('total-price');
    const formulario = document.getElementById('reservaForm');
    const errorAsientos = document.getElementById('errorAsientos');

    // Constante para el precio (8 euros por asiento)
    const PRECIO_POR_ASIENTO = 8;

    // Función para formatear precio en euros
    function formatearPrecio(precio) {
        return precio.toLocaleString('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' €';
    }

    // Función para actualizar el resumen de la reserva
    function actualizarResumen() {
        const asientosSeleccionados = Array.from(asientos)
            .filter(asiento => asiento.checked)
            .map(asiento => asiento.value);
        
        // Actualizar asientos seleccionados
        selectedSeatsElement.textContent = asientosSeleccionados.length > 0 
            ? asientosSeleccionados.join(', ') 
            : '-';
        
        // Calcular y actualizar precio total
        const total = asientosSeleccionados.length * PRECIO_POR_ASIENTO;
        totalPriceElement.textContent = formatearPrecio(total);
    }

    // Añadir listeners a los checkboxes de asientos
    asientos.forEach(asiento => {
        asiento.addEventListener('change', actualizarResumen);
    });

    // Validación del formulario
    formulario.addEventListener('submit', function(e) {
        const asientosSeleccionados = Array.from(asientos)
            .filter(asiento => asiento.checked);

        if (asientosSeleccionados.length === 0) {
            e.preventDefault();
            errorAsientos.style.display = 'flex';
            errorAsientos.classList.remove('shake');
            void errorAsientos.offsetWidth;
            errorAsientos.classList.add('shake');
            errorAsientos.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            setTimeout(() => {
                errorAsientos.style.display = 'none';
            }, 5000);
        }
    });

    // Ocultar mensaje de error cuando se seleccione un asiento
    asientos.forEach(asiento => {
        asiento.addEventListener('change', function() {
            if (this.checked) {
                errorAsientos.style.display = 'none';
            }
        });
    });

    // Inicializar el resumen
    actualizarResumen();
});
</script>
@endsection