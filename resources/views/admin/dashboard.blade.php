@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/peliculas*') ? 'active' : '' }}" href="{{ route('admin.peliculas.index') }}">
                            <i class="fas fa-film"></i> Gestión de Películas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/horarios*') ? 'active' : '' }}" href="{{ route('admin.horarios.index') }}">
                            <i class="fas fa-clock"></i> Gestión de Horarios
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-10 ms-sm-auto px-md-4 bg-dark">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="text-white">Panel de Administración</h1>
            </div>

            <!-- Resumen de estadísticas -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-gradient-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Películas</h5>
                            <h2>24</h2>
                            <p class="mb-0"><small>↑ 3 nuevas esta semana</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-gradient-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Reservas Hoy</h5>
                            <h2>187</h2>
                            <p class="mb-0"><small>↑ 15% vs ayer</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-gradient-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ingresos Hoy</h5>
                            <h2>1.496,00€</h2>
                            <p class="mb-0"><small>↑ 12% vs media semanal</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-gradient-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ocupación Media</h5>
                            <h2>78%</h2>
                            <p class="mb-0"><small>↑ 5% esta semana</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de ingresos -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card bg-dark text-white border">
                        <div class="card-body">
                            <h5 class="card-title">Ingresos últimos 7 días</h5>
                            <canvas id="ingresosSemanaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3 class="text-white">Acciones Rápidas</h3>
                    <div class="btn-group">
                        <a href="{{ route('admin.peliculas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Película
                        </a>
                        <a href="{{ route('admin.horarios.create') }}" class="btn btn-success">
                            <i class="fas fa-clock"></i> Nuevo Horario
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(255, 255, 255, .1);
}

.sidebar .nav-link {
    font-weight: 500;
    padding: 1rem;
}

.sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

main {
    margin-top: 48px;
}

.card {
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a 0%, #13855c 100%);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #36b9cc 0%, #258391 100%);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #f6c23e 0%, #dda20a 100%);
}

.btn-group .btn {
    margin-right: 10px;
}

.border {
    border-color: rgba(255, 255, 255, 0.1) !important;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ingresosSemanaChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Ingresos (€)',
                data: [1250, 1480, 1320, 1690, 2100, 2450, 1496],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        callback: function(value) {
                            return value + '€';
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'rgba(255, 255, 255, 0.8)'
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 