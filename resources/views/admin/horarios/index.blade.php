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
                                <div class="table-responsive">
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
                                                            <form action="{{ route('admin.horarios.destroy', $horario->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-danger" 
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
</style>
@endsection 