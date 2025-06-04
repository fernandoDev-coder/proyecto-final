@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar" id="sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/') }}">
                            <i class="fas fa-home"></i> <span class="menu-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/peliculas*') ? 'active' : '' }}" 
                           href="{{ route('admin.peliculas.index') }}">
                            <i class="fas fa-film"></i> <span class="menu-text">Gestión de Películas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/horarios*') ? 'active' : '' }}" 
                           href="{{ route('admin.horarios.index') }}">
                            <i class="fas fa-clock"></i> <span class="menu-text">Gestión de Horarios</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-3">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-film"></i> TakeYourSeat
                </a>
                <h1 class="h2">Gestión de Horarios</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.horarios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Horario
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Acordeón de películas con sus horarios -->
            <div class="accordion" id="acordeonPeliculas">
                @foreach($peliculas as $pelicula)
                    <div class="accordion-item bg-dark text-white">
                        <h2 class="accordion-header" id="heading{{ $pelicula->id }}">
                            <button class="accordion-button bg-dark text-white collapsed" type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse{{ $pelicula->id }}" 
                                    aria-expanded="false" 
                                    aria-controls="collapse{{ $pelicula->id }}">
                                <div class="d-flex align-items-center w-100">
                                    <img src="{{ url($pelicula->imagen) }}" 
                                         alt="{{ $pelicula->titulo }}" 
                                         class="me-3"
                                         style="width: 50px; height: 75px; object-fit: cover;">
                                    <span class="fs-5">{{ $pelicula->titulo }}</span>
                                    <span class="badge bg-primary ms-auto">{{ $pelicula->horarios->count() }} horarios</span>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $pelicula->id }}" 
                             class="accordion-collapse collapse" 
                             aria-labelledby="heading{{ $pelicula->id }}" 
                             data-bs-parent="#acordeonPeliculas">
                            <div class="accordion-body">
                                @if($pelicula->horarios->isEmpty())
                                    <div class="text-center py-3">
                                        <p class="text-muted mb-3">No hay horarios programados para esta película.</p>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.horarios.create', ['pelicula_id' => $pelicula->id]) }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Agregar Horarios
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <div class="mb-3">
                                            <a href="{{ route('admin.horarios.create', ['pelicula_id' => $pelicula->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus"></i> Agregar Más Horarios
                                            </a>
                                        </div>
                                        <table class="table table-dark table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Sala</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pelicula->horarios as $horario)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}</td>
                                                        <td>{{ $horario->hora }}</td>
                                                        <td>{{ $horario->sala }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="{{ route('admin.horarios.edit', $horario->id) }}" 
                                                                   class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('admin.horarios.destroy', $horario->id) }}" 
                                                                      method="POST" 
                                                                      class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="btn btn-danger btn-sm"
                                                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este horario?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
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

.sidebar .nav-link i {
    margin-right: 12px;
    width: 20px;
    text-align: center;
    font-size: 1.1em;
}

.sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

main {
    margin-left: 260px;
    margin-top: calc(var(--navbar-height) + 1rem);
    padding-right: 1.5rem;
    padding-left: 1rem;
}

.container-fluid {
    padding-left: 0;
    padding-right: 0;
    overflow-x: hidden;
}

.row {
    margin-right: 0;
    margin-left: 0;
    display: flex;
}

.col-md-9 {
    flex: 1;
    padding-left: 0;
    padding-right: 0;
}

.accordion-button:not(.collapsed) {
    background-color: var(--background-dark) !important;
    color: white !important;
}

.accordion-button::after {
    filter: invert(1);
}

.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(255, 255, 255, 0.125);
}

.table-dark {
    --bs-table-bg: transparent;
    --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
    --bs-table-hover-bg: rgba(255, 255, 255, 0.1);
}

.btn-group {
    gap: 0.25rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

/* Navbar brand */
.navbar-brand {
    display: none;
}

/* Estilos de paginación */
.pagination {
    margin-top: 1.5rem;
    gap: 0.5rem;
}

.pagination .page-item .page-link {
    background-color: var(--background-light);
    border: 1px solid var(--border-color);
    color: var(--text-light);
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.pagination .page-item.active .page-link {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.pagination .page-item .page-link:hover {
    background-color: var(--background-lighter);
    border-color: var(--primary-color);
    color: var(--text-light);
}

.pagination .page-item.disabled .page-link {
    background-color: var(--background-dark);
    border-color: var(--border-color);
    color: var(--text-gray);
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('main');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('sidebar-collapsed');
    });

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection 