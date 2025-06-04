@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
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
                <h1 class="h2">Gestión de Películas</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.peliculas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Película
                    </a>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-gradient-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Películas</h5>
                            <h2>{{ $peliculas->total() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Reservas Hoy</h5>
                            <h2>187</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ingresos Hoy</h5>
                            <h2>1.496,00€</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-gradient-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Ocupación Media</h5>
                            <h2>78%</h2>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabla -->
            <div class="card bg-dark">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Géneros</th>
                                    <th>Clasificación</th>
                                    <th>Duración</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peliculas as $pelicula)
                                    <tr>
                                        <td>{{ $pelicula->id }}</td>
                                        <td>{{ $pelicula->titulo }}</td>
                                        <td>{{ $pelicula->generos->pluck('nombre')->join(', ') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $pelicula->clasificacion }}</span>
                                        </td>
                                        <td>{{ $pelicula->duracion }} min</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('peliculas.show', $pelicula->id) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.peliculas.edit', $pelicula->id) }}" 
                                                   class="btn btn-warning btn-sm"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.peliculas.destroy', $pelicula->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta película?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="empty-state">
                                                <i class="fas fa-film fa-3x mb-3"></i>
                                                <p>No hay películas registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $peliculas->links() }}
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

/* Cards */
.card {
    border: 1px solid rgba(255, 255, 255, 0.1);
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

.card-title {
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.5rem;
}

.card h2 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0;
}

/* Table */
.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

/* Buttons */
.btn-group {
    gap: 0.25rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

/* Alert */
.alert {
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border-color: #28a745;
    color: #28a745;
}

/* Navbar brand */
.navbar-brand {
    display: none;
}

/* Container fluid */
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
@endsection 