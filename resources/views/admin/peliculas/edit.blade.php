@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <nav class="col-md-2 bg-dark sidebar" id="adminSidebar">
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
        <main class="col px-3">
            <div class="container-fluid py-4">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h1 class="h2">Editar Película: {{ $pelicula->titulo }}</h1>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Volver al listado</span>
                                </a>
                            </div>
                        </div>

                        <div class="card bg-dark">
                            <div class="card-body">
                                <form action="{{ route('admin.peliculas.update', $pelicula->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="titulo" class="form-label">Título</label>
                                                <input type="text" 
                                                       class="form-control @error('titulo') is-invalid @enderror" 
                                                       id="titulo" 
                                                       name="titulo" 
                                                       value="{{ old('titulo', $pelicula->titulo) }}" 
                                                       required>
                                                @error('titulo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción</label>
                                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                                          id="descripcion" 
                                                          name="descripcion" 
                                                          rows="3" 
                                                          required>{{ old('descripcion', $pelicula->descripcion) }}</textarea>
                                                @error('descripcion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="generos" class="form-label">Géneros</label>
                                                <select class="form-select bg-dark text-white @error('generos') is-invalid @enderror" 
                                                        id="generos" 
                                                        name="generos[]" 
                                                        multiple
                                                        required>
                                                    <option value="Acción" {{ in_array('Acción', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Acción</option>
                                                    <option value="Aventura" {{ in_array('Aventura', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Aventura</option>
                                                    <option value="Comedia" {{ in_array('Comedia', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Comedia</option>
                                                    <option value="Drama" {{ in_array('Drama', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Drama</option>
                                                    <option value="Ciencia ficción" {{ in_array('Ciencia ficción', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Ciencia ficción</option>
                                                    <option value="Terror" {{ in_array('Terror', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Terror</option>
                                                    <option value="Romance" {{ in_array('Romance', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Romance</option>
                                                    <option value="Documental" {{ in_array('Documental', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Documental</option>
                                                    <option value="Animación" {{ in_array('Animación', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Animación</option>
                                                    <option value="Suspenso" {{ in_array('Suspenso', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Suspenso</option>
                                                    <option value="Crimen" {{ in_array('Crimen', $pelicula->generos->pluck('nombre')->toArray()) ? 'selected' : '' }}>Crimen</option>
                                                </select>
                                                <small class="form-text text-white-50">Mantén presionada la tecla Ctrl (Windows) o Command (Mac) para seleccionar múltiples géneros</small>
                                                @error('generos')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="duracion" class="form-label">Duración (minutos)</label>
                                                <input type="number" 
                                                       class="form-control @error('duracion') is-invalid @enderror" 
                                                       id="duracion" 
                                                       name="duracion" 
                                                       value="{{ old('duracion', $pelicula->duracion) }}" 
                                                       required>
                                                @error('duracion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="clasificacion" class="form-label">Clasificación</label>
                                                <select class="form-select @error('clasificacion') is-invalid @enderror" 
                                                        id="clasificacion" 
                                                        name="clasificacion" 
                                                        required>
                                                    <option value="">Selecciona una clasificación</option>
                                                    <option value="G" {{ old('clasificacion', $pelicula->clasificacion) == 'G' ? 'selected' : '' }}>G (Público General)</option>
                                                    <option value="PG" {{ old('clasificacion', $pelicula->clasificacion) == 'PG' ? 'selected' : '' }}>PG (Guía Parental)</option>
                                                    <option value="PG-13" {{ old('clasificacion', $pelicula->clasificacion) == 'PG-13' ? 'selected' : '' }}>PG-13 (Guía Parental Estricta)</option>
                                                    <option value="R" {{ old('clasificacion', $pelicula->clasificacion) == 'R' ? 'selected' : '' }}>R (Restringido)</option>
                                                    <option value="NC-17" {{ old('clasificacion', $pelicula->clasificacion) == 'NC-17' ? 'selected' : '' }}>NC-17 (Solo Adultos)</option>
                                                </select>
                                                @error('clasificacion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="imagen" class="form-label">Imagen de la Película</label>
                                                <input type="file" 
                                                       class="form-control @error('imagen') is-invalid @enderror" 
                                                       id="imagen" 
                                                       name="imagen" 
                                                       accept="image/*">
                                                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 10MB. Deja este campo vacío si no quieres cambiar la imagen actual.</small>
                                                @error('imagen')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i>
                                            <span>Actualizar Película</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
    margin-right: 0.75rem;
    width: 20px;
    text-align: center;
}

main {
    margin-left: 260px;
    margin-top: calc(var(--navbar-height));
    min-height: calc(100vh - var(--navbar-height));
    background-color: var(--background-dark);
    transition: all 0.3s ease;
}

.form-control, .form-select {
    background-color: var(--background-light);
    border-color: var(--border-color);
    color: var(--text-light);
}

.form-control:focus, .form-select:focus {
    background-color: var(--background-light);
    border-color: var(--primary-color);
    color: var(--text-light);
    box-shadow: 0 0 0 0.25rem rgba(229, 9, 20, 0.25);
}

.form-control::placeholder {
    color: var(--text-gray);
}

.card {
    border-color: var(--border-color);
    background-color: var(--background-light);
}

.form-text {
    color: var(--text-gray);
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
}
</style>
@endsection 