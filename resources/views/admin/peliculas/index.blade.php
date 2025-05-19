@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/peliculas*') ? 'active' : '' }}" 
                           href="{{ route('admin.peliculas.index') }}">
                            <i class="fas fa-film"></i> Gestión de Películas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('admin/horarios*') ? 'active' : '' }}" 
                           href="{{ route('admin.horarios.index') }}">
                            <i class="fas fa-clock"></i> Gestión de Horarios
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Gestión de Películas</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.peliculas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Película
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabla de películas -->
            <div class="card bg-dark">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Título</th>
                                    <th>Género</th>
                                    <th>Clasificación</th>
                                    <th>Duración</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peliculas as $pelicula)
                                <tr>
                                    <td>{{ $pelicula->id }}</td>
                                    <td>
                                        @if($pelicula->imagen)
                                            <img src="{{ url($pelicula->imagen) }}" 
                                                 alt="{{ $pelicula->titulo }}" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 50px; height: 75px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/no-image.jpg') }}" 
                                                 alt="No imagen disponible" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 50px; height: 75px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>{{ $pelicula->titulo }}</td>
                                    <td>{{ $pelicula->genero }}</td>
                                    <td>{{ $pelicula->clasificacion }}</td>
                                    <td>{{ $pelicula->duracion }} min</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.peliculas.edit', $pelicula->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.peliculas.generar-horarios', $pelicula->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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

.table-dark {
    --bs-table-bg: var(--background-light);
    --bs-table-striped-bg: var(--background-dark);
    --bs-table-hover-bg: rgba(255, 255, 255, 0.1);
}

.pagination {
    --bs-pagination-color: #fff;
    --bs-pagination-bg: #343a40; /* Gris oscuro */
    --bs-pagination-border-color: #454d55;
    --bs-pagination-hover-color: #fff;
    --bs-pagination-hover-bg: #23272b; /* Gris más oscuro */
    --bs-pagination-hover-border-color: #454d55;
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: #dc3545; /* Rojo */
    --bs-pagination-active-border-color: #dc3545;
    --bs-pagination-disabled-color: #6c757d;
    --bs-pagination-disabled-bg: #343a40;
    --bs-pagination-disabled-border-color: #454d55;
}

.page-link {
    background-color: #343a40; /* Gris oscuro */
    border: 1px solid #454d55;
    color: #fff;
    margin: 0 2px;
}

.page-link:hover {
    background-color: #23272b; /* Gris más oscuro */
    border-color: #454d55;
    color: #fff;
}

.page-item.active .page-link {
    background-color: #dc3545; /* Rojo */
    border-color: #dc3545;
    color: #fff;
}

.page-item.disabled .page-link {
    background-color: #343a40; /* Gris oscuro */
    border-color: #454d55;
    color: #6c757d;
}

.btn-group {
    gap: 0.25rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

.img-thumbnail {
    background-color: var(--background-light);
    border-color: var(--border-color);
}

/* Estilos específicos de paginación */
nav[aria-label="Pagination Navigation"] {
    margin-top: 20px;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.pagination > li {
    list-style: none;
}

.pagination .page-item .page-link {
    background-color: #343a40 !important;
    border: 1px solid #454d55 !important;
    color: #fff !important;
    padding: 8px 12px;
    border-radius: 4px;
}

.pagination .page-item.active .page-link {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: #fff !important;
}

.pagination .page-item .page-link:hover {
    background-color: #23272b !important;
    border-color: #454d55 !important;
    color: #fff !important;
}

.pagination .page-item.disabled .page-link {
    background-color: #343a40 !important;
    border-color: #454d55 !important;
    color: #6c757d !important;
    cursor: not-allowed;
}

/* Ajuste adicional para la tabla */
.table-responsive {
    margin-bottom: 20px;
}
</style>
@endsection 