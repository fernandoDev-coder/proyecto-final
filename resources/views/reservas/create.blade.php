@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-dark">
                <div class="card-header">
                    <h2 class="mb-0"><i class="fas fa-ticket-alt"></i> Reservar Entradas</h2>
                </div>

                <div class="card-body">
                    <div class="movie-info mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                @if($pelicula->imagen)
                                    <img src="{{ asset($pelicula->imagen) }}" 
                                         alt="{{ $pelicula->titulo }}" 
                                         class="img-fluid rounded">
                                @else
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-film fa-4x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h3>{{ $pelicula->titulo }}</h3>
                                <p>
                                    <i class="fas fa-clock"></i> {{ $pelicula->duracion }} minutos |
                                    <i class="fas fa-star"></i> {{ $pelicula->clasificacion }}
                                </p>
                                <p>{{ $pelicula->descripcion }}</p>
                            </div>
                        </div>
                    </div>

                    @if($horarios->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> No hay horarios disponibles para esta película.
                        </div>
                    @else
                        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                            @csrf
                            
                            <div class="horarios-container mb-4">
                                <h4 class="mb-3"><i class="fas fa-calendar-alt"></i> Horarios Disponibles</h4>
                                <div class="horarios-grid">
                                    @foreach($horarios as $horario)
                                        <div class="horario-card" data-horario-id="{{ $horario->id }}">
                                            <div class="horario-info">
                                                <div class="fecha">
                                                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}
                                                </div>
                                                <div class="hora">
                                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($horario->hora)->format('H:i') }}
                                                </div>
                                                <div class="sala">
                                                    <i class="fas fa-door-open"></i> Sala {{ $horario->sala }}
                                                </div>
                                                <div class="disponibles">
                                                    <i class="fas fa-chair"></i> {{ $horario->asientos_disponibles }} asientos libres
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <input type="hidden" name="horario_id" id="horario_id">
                            <input type="hidden" name="cantidad_asientos" id="cantidad_asientos">
                            <input type="hidden" name="asientos" id="asientos-seleccionados">

                            <div id="asientos-container" class="mb-4" style="display: none;">
                                <h4 class="mb-3">Selección de Asientos <span id="horario-seleccionado"></span></h4>
                                <div class="text-center mb-3">
                                    <div class="screen">PANTALLA</div>
                                    <div class="seat-map">
                                        @foreach(range('A', 'H') as $fila)
                                            <div class="seat-row">
                                                <span class="row-label">{{ $fila }}</span>
                                                @foreach(range(1, 10) as $numero)
                                                    <div class="seat" 
                                                         data-asiento="{{ $fila }}{{ $numero }}"
                                                         data-toggle="tooltip"
                                                         title="Asiento {{ $fila }}{{ $numero }}">
                                                        <span class="seat-icon"><i class="fas fa-chair"></i></span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="seat-info text-center mb-3">
                                    <div class="d-inline-block mx-3">
                                        <div class="seat-example available"><i class="fas fa-chair"></i></div>
                                        <span>Disponible</span>
                                    </div>
                                    <div class="d-inline-block mx-3">
                                        <div class="seat-example selected"><i class="fas fa-chair"></i></div>
                                        <span>Seleccionado</span>
                                    </div>
                                    <div class="d-inline-block mx-3">
                                        <div class="seat-example occupied"><i class="fas fa-times"></i></div>
                                        <span>Ocupado</span>
                                    </div>
                                </div>
                                <div class="text-center mb-3">
                                    <span class="badge bg-primary" id="asientos-count"></span>
                                </div>

                                <!-- Resumen de compra -->
                                <div id="resumen-compra" class="card bg-dark mb-4" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Resumen de tu compra</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Asientos seleccionados:</strong> <span id="asientos-lista"></span></p>
                                                <p><strong>Precio por asiento:</strong> 8,00 €</p>
                                                <p><strong>Total a pagar:</strong> <span id="precio-total">0,00 €</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Película:</strong> {{ $pelicula->titulo }}</p>
                                                <p><strong>Fecha:</strong> <span id="resumen-fecha"></span></p>
                                                <p><strong>Hora:</strong> <span id="resumen-hora"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pasarela de pago -->
                                <div id="pasarela-pago" class="card bg-dark mb-4" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Método de pago</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Número de tarjeta</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Titular de la tarjeta</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text" class="form-control" placeholder="Nombre del titular" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Fecha de caducidad</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    <input type="text" class="form-control" placeholder="MM/AA" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">CVV</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    <input type="text" class="form-control" placeholder="123" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="payment-methods text-center mb-3">
                                            <i class="fab fa-cc-visa fa-2x mx-2"></i>
                                            <i class="fab fa-cc-mastercard fa-2x mx-2"></i>
                                            <i class="fab fa-cc-amex fa-2x mx-2"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('peliculas.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="btn-confirmar" style="display: none;">
                                        <i class="fas fa-check-circle"></i> Confirmar y Pagar
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.no-image-placeholder {
    background-color: var(--background-lighter);
    border-radius: 8px;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-gray);
}

.card {
    border-color: var(--border-color);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: var(--background-lighter);
    border-bottom-color: var(--border-color);
}

.movie-info {
    background-color: var(--background-lighter);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.alert {
    background-color: rgba(255, 193, 7, 0.1);
    border-color: #ffc107;
    color: #ffc107;
}

/* Estilos para los horarios */
.horarios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.horario-card {
    background: var(--background-lighter);
    border-radius: 12px;
    padding: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.horario-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    border-color: var(--primary-color);
}

.horario-card.selected {
    background: var(--background-light);
    border-color: var(--primary-color);
}

.horario-info {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.horario-info > div {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
}

.horario-info i {
    width: 20px;
    text-align: center;
    color: var(--primary-color);
}

/* Estilos para el mapa de asientos */
.screen {
    background: linear-gradient(45deg, #2b2b2b, #3a3a3a);
    height: 40px;
    width: 80%;
    margin: 0 auto 40px;
    transform: perspective(300px) rotateX(-10deg);
    box-shadow: 0 3px 10px rgba(255,255,255,0.1);
    text-align: center;
    line-height: 40px;
    color: #fff;
    font-weight: bold;
    border-radius: 5px;
    font-size: 1.2rem;
}

.seat-map {
    display: inline-block;
    background: rgba(0,0,0,0.2);
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: inset 0 0 15px rgba(0,0,0,0.2);
}

.seat-row {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.row-label {
    margin-right: 15px;
    width: 25px;
    text-align: center;
    font-weight: bold;
    font-size: 1.1rem;
    color: var(--text-light);
}

.seat {
    width: 40px;
    height: 40px;
    margin: 4px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background-color: var(--background-lighter);
    color: var(--text-light);
    transition: all 0.3s ease;
}

.seat:hover:not(.occupied) {
    transform: scale(1.1);
    background-color: var(--primary-color);
}

.seat.selected {
    background-color: var(--primary-color);
    transform: scale(1.05);
}

.seat.selected i {
    color: white;
}

.seat.occupied {
    background-color: #ff4444 !important;
    cursor: not-allowed !important;
    pointer-events: none;
}

.seat.occupied .seat-icon {
    color: white;
}

.seat.occupied:hover {
    transform: none !important;
    background-color: #ff4444 !important;
}

.seat-icon {
    font-size: 1.2rem;
}

.seat-label {
    font-size: 0.75rem;
    font-weight: 500;
}

.seat.selected .seat-label {
    color: white;
}

.seat-info {
    margin-top: 25px;
    background: rgba(0,0,0,0.2);
    padding: 15px;
    border-radius: 8px;
    display: inline-block;
}

.seat-example {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin-bottom: 5px;
    background-color: var(--background-lighter);
}

.seat-example.selected {
    background-color: var(--primary-color);
    color: white;
}

.seat-example.occupied {
    background-color: var(--background-dark);
    opacity: 0.5;
}

.badge {
    font-size: 1rem;
    padding: 8px 16px;
    border-radius: 20px;
}

.btn {
    padding: 10px 20px;
    font-size: 1.1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Animaciones */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.seat.selected {
    animation: pulse 1s infinite;
}

.payment-methods i {
    color: var(--text-gray);
    transition: all 0.3s ease;
}

.payment-methods i:hover {
    color: var(--primary-color);
}

.input-group-text {
    background-color: var(--background-lighter);
    border-color: var(--border-color);
    color: var(--text-light);
}

.form-control:disabled {
    background-color: var(--background-lighter);
    border-color: var(--border-color);
    color: var(--text-gray);
    cursor: not-allowed;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const horarioCards = document.querySelectorAll('.horario-card');
    const asientosContainer = document.getElementById('asientos-container');
    const horarioIdInput = document.getElementById('horario_id');
    const cantidadInput = document.getElementById('cantidad_asientos');
    const asientosSeleccionadosInput = document.getElementById('asientos-seleccionados');
    const horarioSeleccionadoSpan = document.getElementById('horario-seleccionado');
    const asientosCountSpan = document.getElementById('asientos-count');
    const btnConfirmar = document.getElementById('btn-confirmar');
    const form = document.getElementById('reservaForm');
    let asientosSeleccionados = [];

    const resumenCompra = document.getElementById('resumen-compra');
    const pasarelaPago = document.getElementById('pasarela-pago');
    const asientosLista = document.getElementById('asientos-lista');
    const precioTotal = document.getElementById('precio-total');
    const resumenFecha = document.getElementById('resumen-fecha');
    const resumenHora = document.getElementById('resumen-hora');
    const PRECIO_POR_ASIENTO = 8.00;

    // Función para cargar los asientos ocupados
    async function cargarAsientosOcupados(horarioId) {
        try {
            console.log('Iniciando carga de asientos ocupados para horario:', horarioId);
            const response = await fetch(`/api/horarios/${horarioId}/asientos-ocupados`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error en la respuesta:', response.status, errorText);
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Datos recibidos:', data);
            if (!data.asientos) {
                console.warn('No se recibieron asientos en la respuesta');
            }
            return data.asientos || [];
        } catch (error) {
            console.error('Error al cargar asientos:', error);
            alert('Error al cargar los asientos ocupados. Por favor, intenta de nuevo.');
            return [];
        }
    }

    // Función para actualizar el estado visual de los asientos
    function actualizarAsientos(asientosOcupados) {
        console.log('Asientos ocupados:', asientosOcupados); // Debug
        document.querySelectorAll('.seat').forEach(seat => {
            const asiento = seat.dataset.asiento;
            if (asientosOcupados.includes(asiento)) {
                seat.classList.add('occupied');
                seat.classList.remove('selected');
                seat.style.backgroundColor = '#ff4444'; // Color rojo para asientos ocupados
                seat.style.cursor = 'not-allowed';
                seat.querySelector('.seat-icon').innerHTML = '<i class="fas fa-times"></i>';
                const index = asientosSeleccionados.indexOf(asiento);
                if (index > -1) asientosSeleccionados.splice(index, 1);
            } else {
                seat.classList.remove('occupied');
                seat.style.backgroundColor = ''; // Restaurar color por defecto
                seat.style.cursor = 'pointer';
                seat.querySelector('.seat-icon').innerHTML = '<i class="fas fa-chair"></i>';
            }
        });
        actualizarContadores();
    }

    // Función para actualizar contadores y estados
    function actualizarContadores() {
        asientosSeleccionadosInput.value = asientosSeleccionados.join(',');
        cantidadInput.value = asientosSeleccionados.length;
        asientosCountSpan.innerHTML = '';
        btnConfirmar.style.display = asientosSeleccionados.length > 0 ? 'block' : 'none';
        actualizarResumenCompra();
    }

    function actualizarResumenCompra() {
        if (asientosSeleccionados.length > 0) {
            asientosLista.textContent = asientosSeleccionados.join(', ');
            const total = asientosSeleccionados.length * PRECIO_POR_ASIENTO;
            precioTotal.textContent = total.toFixed(2) + ' €';
            
            const horarioCard = document.querySelector('.horario-card.selected');
            if (horarioCard) {
                const fecha = horarioCard.querySelector('.fecha').textContent.trim();
                const hora = horarioCard.querySelector('.hora').textContent.trim();
                resumenFecha.textContent = fecha.replace('📅', '').trim();
                resumenHora.textContent = hora.replace('🕐', '').trim();
            }

            resumenCompra.style.display = 'block';
            pasarelaPago.style.display = 'block';
        } else {
            resumenCompra.style.display = 'none';
            pasarelaPago.style.display = 'none';
        }
    }

    // Función para seleccionar un horario
    async function seleccionarHorario(horarioCard) {
        try {
            horarioCards.forEach(c => c.classList.remove('selected'));
            horarioCard.classList.add('selected');
            
            const horarioId = horarioCard.dataset.horarioId;
            horarioIdInput.value = horarioId;
            
            const fecha = horarioCard.querySelector('.fecha').textContent.trim();
            const hora = horarioCard.querySelector('.hora').textContent.trim();
            const sala = horarioCard.querySelector('.sala').textContent.trim();
            horarioSeleccionadoSpan.textContent = `${fecha} ${hora} ${sala}`;
            
            asientosContainer.style.display = 'block';
            
            console.log('Cargando asientos ocupados para horario:', horarioId); // Debug
            const asientosOcupados = await cargarAsientosOcupados(horarioId);
            console.log('Asientos ocupados cargados:', asientosOcupados); // Debug
            
            // Limpiar selecciones previas
            asientosSeleccionados = [];
            document.querySelectorAll('.seat').forEach(seat => {
                seat.classList.remove('selected');
                seat.classList.remove('occupied');
                seat.style.backgroundColor = '';
                seat.style.cursor = 'pointer';
                seat.querySelector('.seat-icon').innerHTML = '<i class="fas fa-chair"></i>';
            });
            
            // Actualizar estado de asientos
            actualizarAsientos(asientosOcupados);
            actualizarContadores();
            
        } catch (error) {
            console.error('Error al seleccionar horario:', error);
            alert('Error al cargar los asientos. Por favor, intenta de nuevo.');
        }
    }

    // Event listeners para las tarjetas de horarios
    horarioCards.forEach(card => {
        card.addEventListener('click', () => seleccionarHorario(card));
    });

    // Event listener para la selección de asientos
    document.querySelectorAll('.seat').forEach(seat => {
        seat.addEventListener('click', function() {
            if (this.classList.contains('occupied')) return;

            const asiento = this.dataset.asiento;
            const index = asientosSeleccionados.indexOf(asiento);

            if (index > -1) {
                asientosSeleccionados.splice(index, 1);
                this.classList.remove('selected');
                this.querySelector('.seat-icon').innerHTML = '<i class="fas fa-chair"></i>';
            } else {
                if (asientosSeleccionados.length < 10) {
                    asientosSeleccionados.push(asiento);
                    this.classList.add('selected');
                    this.querySelector('.seat-icon').innerHTML = '<i class="fas fa-chair"></i>';
                } else {
                    alert('No puedes seleccionar más de 10 asientos por reserva.');
                }
            }

            actualizarContadores();
        });
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        if (!horarioIdInput.value) {
            e.preventDefault();
            alert('Por favor, selecciona un horario.');
            return;
        }
        if (asientosSeleccionados.length === 0) {
            e.preventDefault();
            alert('Por favor, selecciona al menos un asiento.');
            return;
        }
    });

    // Si solo hay un horario disponible (viniendo desde crear-desde-horario), seleccionarlo automáticamente
    if (horarioCards.length === 1) {
        await seleccionarHorario(horarioCards[0]);
    }
});
</script>
@endpush

@endsection