@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 bg-dark sidebar" id="adminSidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/') }}">
                            <i class="fas fa-home"></i> <span class="menu-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/peliculas*') ? 'active' : '' }}" href="{{ route('admin.peliculas.index') }}">
                            <i class="fas fa-film"></i> <span class="menu-text">Gestión de Películas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/horarios*') ? 'active' : '' }}" href="{{ route('admin.horarios.index') }}">
                            <i class="fas fa-clock"></i> <span class="menu-text">Gestión de Horarios</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-0">
            
            <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom px-3">
                <h1 class="text-white m-0">Panel de Administración</h1>
            </div>

            <!-- Resumen de estadísticas -->
            <div class="row">
                <div class="col-12 col-md-3 mb-4">
                    <div class="card bg-gradient-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Películas</h5>
                            <h2>{{ $stats['total_peliculas'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-4">
                    <div class="card bg-gradient-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Reservas Hoy</h5>
                            <h2>{{ $stats['reservas_hoy'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-4">
                    <div class="card bg-gradient-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ingresos Hoy</h5>
                            <h2>{{ $stats['ingresos_hoy'] }}€</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-4">
                    <div class="card bg-gradient-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ocupación Media</h5>
                            <h2>{{ $stats['ocupacion_media'] }}%</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de ingresos -->
            <div class="row mt-4">
                <div class="col-12">
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
                <div class="col-12">
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
    top: var(--navbar-height);
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 1rem 0 0;
    box-shadow: inset -1px 0 0 rgba(255, 255, 255, .1);
    width: 260px;
    background-color: #1a1a1a;
    transition: all 0.3s ease;
}

.sidebar .nav-link {
    font-weight: 500;
    padding: 1rem;
    white-space: nowrap;
    color: #fff;
    display: flex;
    align-items: center;
    transition: all 0.3s;
}

.sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.sidebar .nav-link i {
    margin-right: 12px;
    width: 20px;
    text-align: center;
    font-size: 1.1em;
}

main {
    margin-left: 260px;
    margin-top: calc(var(--navbar-height) + 1rem);
    padding-right: 1.5rem;
    padding-left: 0.5rem;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 100%;
        max-width: 260px;
    }

    .sidebar.show {
        transform: translateX(0);
    }

    main {
        margin-left: 0;
        padding: 1rem;
        width: 100%;
    }

    .card {
        margin-bottom: 1rem;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn-group .btn {
        width: 100%;
        margin: 0;
    }
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
            labels: {!! json_encode(array_column($ingresosSemana, 'dia')) !!},
            datasets: [{
                label: 'Ingresos (€)',
                data: {!! json_encode(array_column($ingresosSemana, 'ingresos')) !!},
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