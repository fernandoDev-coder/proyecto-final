@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h2 class="mb-0">
                        <i class="fas fa-chart-line"></i> Dashboard de Informes
                    </h2>
                </div>

                <div class="card-body">
                    <!-- Filtros de fecha -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="date" id="fecha_desde" class="form-control bg-dark text-white">
                                <span class="input-group-text bg-dark text-white">hasta</span>
                                <input type="date" id="fecha_hasta" class="form-control bg-dark text-white">
                                <button class="btn btn-primary" onclick="actualizarInformes()">
                                    <i class="fas fa-sync"></i> Actualizar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjetas de resumen -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-euro-sign"></i> Ingresos Totales
                                    </h5>
                                    <h3 class="mb-0">{{ number_format($estadisticas['total_ingresos'], 2, ',', '.') }} €</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-ticket-alt"></i> Total Reservas
                                    </h5>
                                    <h3 class="mb-0">{{ $estadisticas['total_reservas'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row">
                        <!-- Gráfico de Ingresos por Mes -->
                        <div class="col-md-6 mb-4">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Ingresos por Mes</h5>
                                    <canvas id="ingresosPorMes"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Ocupación de Salas -->
                        <div class="col-md-6 mb-4">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Ocupación de Salas</h5>
                                    <canvas id="ocupacionSalas"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Películas más vistas -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Películas Más Vistas</h5>
                                    <div class="table-responsive">
                                        <table class="table table-dark table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Película</th>
                                                    <th>Total Reservas</th>
                                                    <th>Porcentaje de Ocupación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($estadisticas['peliculas_mas_vistas'] as $pelicula)
                                                <tr>
                                                    <td>{{ $pelicula->titulo }}</td>
                                                    <td>{{ $pelicula->total_reservas }}</td>
                                                    <td>
                                                        <div class="progress bg-dark">
                                                            @php
                                                                $porcentaje = $estadisticas['total_reservas'] > 0 
                                                                    ? ($pelicula->total_reservas / $estadisticas['total_reservas']) * 100 
                                                                    : 0;
                                                            @endphp
                                                            <div class="progress-bar bg-primary" 
                                                                role="progressbar" 
                                                                style="width: {{ $porcentaje }}%"
                                                                aria-valuenow="{{ $porcentaje }}" 
                                                                aria-valuemin="0" 
                                                                aria-valuemax="100">
                                                                {{ round($porcentaje, 1) }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para el gráfico de ingresos por mes
    const ingresosPorMes = @json($estadisticas['ingresos_por_mes']);
    const ctxIngresos = document.getElementById('ingresosPorMes').getContext('2d');
    new Chart(ctxIngresos, {
        type: 'line',
        data: {
            labels: ingresosPorMes.map(i => `${i.mes}/${i.año}`),
            datasets: [{
                label: 'Ingresos',
                data: ingresosPorMes.map(i => i.total),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: 'white'
                    }
                },
                x: {
                    ticks: {
                        color: 'white'
                    }
                }
            }
        }
    });

    // Datos para el gráfico de ocupación de salas
    const ocupacionSalas = @json($estadisticas['ocupacion_salas']);
    const ctxOcupacion = document.getElementById('ocupacionSalas').getContext('2d');
    new Chart(ctxOcupacion, {
        type: 'bar',
        data: {
            labels: Object.keys(ocupacionSalas),
            datasets: [{
                label: 'Porcentaje de Ocupación',
                data: Object.values(ocupacionSalas).map(s => s.porcentaje),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        color: 'white'
                    }
                },
                x: {
                    ticks: {
                        color: 'white'
                    }
                }
            }
        }
    });
});

function actualizarInformes() {
    const desde = document.getElementById('fecha_desde').value;
    const hasta = document.getElementById('fecha_hasta').value;
    
    fetch(`/informes/exportar?desde=${desde}&hasta=${hasta}`)
        .then(response => response.json())
        .then(data => {
            // Actualizar los datos en la página
            location.reload();
        })
        .catch(error => console.error('Error:', error));
}
</script>
@endpush

<style>
.card {
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 1rem;
}

.progress {
    height: 20px;
}

.table-dark {
    background-color: transparent;
}

.table-dark td, .table-dark th {
    border-color: rgba(255, 255, 255, 0.1);
}

.input-group-text {
    border-color: rgba(255, 255, 255, 0.1);
}

.form-control:focus {
    background-color: var(--background-lighter);
    border-color: var(--primary-color);
    color: var(--text-light);
    box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
}
</style>
@endsection 