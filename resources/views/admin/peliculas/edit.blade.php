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
                <h1 class="h2">Editar Película: {{ $pelicula->titulo }}</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al listado
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
                                    <label for="genero" class="form-label">Género</label>
                                    <select class="form-select @error('genero') is-invalid @enderror" 
                                            id="genero" 
                                            name="genero" 
                                            required>
                                        <option value="">Selecciona un género</option>
                                        <option value="Acción" {{ old('genero', $pelicula->genero) == 'Acción' ? 'selected' : '' }}>Acción</option>
                                        <option value="Aventura" {{ old('genero', $pelicula->genero) == 'Aventura' ? 'selected' : '' }}>Aventura</option>
                                        <option value="Comedia" {{ old('genero', $pelicula->genero) == 'Comedia' ? 'selected' : '' }}>Comedia</option>
                                        <option value="Drama" {{ old('genero', $pelicula->genero) == 'Drama' ? 'selected' : '' }}>Drama</option>
                                        <option value="Ciencia ficción" {{ old('genero', $pelicula->genero) == 'Ciencia ficción' ? 'selected' : '' }}>Ciencia ficción</option>
                                        <option value="Terror" {{ old('genero', $pelicula->genero) == 'Terror' ? 'selected' : '' }}>Terror</option>
                                        <option value="Romance" {{ old('genero', $pelicula->genero) == 'Romance' ? 'selected' : '' }}>Romance</option>
                                        <option value="Documental" {{ old('genero', $pelicula->genero) == 'Documental' ? 'selected' : '' }}>Documental</option>
                                        <option value="Animación" {{ old('genero', $pelicula->genero) == 'Animación' ? 'selected' : '' }}>Animación</option>
                                    </select>
                                    @error('genero')
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
                                <i class="fas fa-save"></i> Actualizar Película
                            </button>
                        </div>
                    </form>
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

.form-control, .form-select {
    background-color: var(--background-light);
    border-color: var(--border-color);
    color: var(--text-color);
}

.form-control:focus, .form-select:focus {
    background-color: var(--background-light);
    border-color: var(--primary-color);
    color: var(--text-color);
    box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.card {
    border-color: var(--border-color);
}

.form-text {
    color: var(--text-muted);
}
</style>
@endsection 