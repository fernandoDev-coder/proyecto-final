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
        <main class="col-md-10 ms-sm-auto px-4">
            <div class="container-fluid py-4">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                            <h1 class="h2 text-white">Nueva Película</h1>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                    <span class="ms-1">Volver al listado</span>
                                </a>
                            </div>
                        </div>

                        <div class="card bg-dark border">
                            <div class="card-body p-4">
                                <form action="{{ route('admin.peliculas.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    @csrf

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="titulo" class="form-label">Título</label>
                                                <input type="text" 
                                                       class="form-control form-control-lg @error('titulo') is-invalid @enderror" 
                                                       id="titulo" 
                                                       name="titulo" 
                                                       value="{{ old('titulo') }}" 
                                                       required>
                                                @error('titulo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-4">
                                                <label for="descripcion" class="form-label">Descripción</label>
                                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                                          id="descripcion" 
                                                          name="descripcion" 
                                                          rows="5" 
                                                          required>{{ old('descripcion') }}</textarea>
                                                @error('descripcion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-4">
                                                <label for="generos" class="form-label">Géneros</label>
                                                <select class="form-select form-select-lg bg-dark text-white @error('generos') is-invalid @enderror" 
                                                        id="generos" 
                                                        name="generos[]" 
                                                        multiple
                                                        required
                                                        size="8">
                                                    <option value="Acción">Acción</option>
                                                    <option value="Aventura">Aventura</option>
                                                    <option value="Comedia">Comedia</option>
                                                    <option value="Drama">Drama</option>
                                                    <option value="Ciencia ficción">Ciencia ficción</option>
                                                    <option value="Terror">Terror</option>
                                                    <option value="Romance">Romance</option>
                                                    <option value="Documental">Documental</option>
                                                    <option value="Animación">Animación</option>
                                                    <option value="Suspenso">Suspenso</option>
                                                    <option value="Crimen">Crimen</option>
                                                </select>
                                                <small class="form-text text-white-50">Mantén presionada la tecla Ctrl (Windows) o Command (Mac) para seleccionar múltiples géneros</small>
                                                @error('generos')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="duracion" class="form-label">Duración (minutos)</label>
                                                <input type="number" 
                                                       class="form-control form-control-lg @error('duracion') is-invalid @enderror" 
                                                       id="duracion" 
                                                       name="duracion" 
                                                       value="{{ old('duracion') }}" 
                                                       min="1"
                                                       required>
                                                @error('duracion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-4">
                                                <label for="clasificacion" class="form-label">Clasificación</label>
                                                <select class="form-select form-select-lg @error('clasificacion') is-invalid @enderror" 
                                                        id="clasificacion" 
                                                        name="clasificacion" 
                                                        required>
                                                    <option value="">Selecciona una clasificación</option>
                                                    <option value="G" {{ old('clasificacion') == 'G' ? 'selected' : '' }}>G (Público General)</option>
                                                    <option value="PG" {{ old('clasificacion') == 'PG' ? 'selected' : '' }}>PG (Guía Parental)</option>
                                                    <option value="PG-13" {{ old('clasificacion') == 'PG-13' ? 'selected' : '' }}>PG-13 (Guía Parental Estricta)</option>
                                                    <option value="R" {{ old('clasificacion') == 'R' ? 'selected' : '' }}>R (Restringido)</option>
                                                    <option value="NC-17" {{ old('clasificacion') == 'NC-17' ? 'selected' : '' }}>NC-17 (Solo Adultos)</option>
                                                </select>
                                                @error('clasificacion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-4">
                                                <label for="imagen" class="form-label">Imagen de la Película</label>
                                                <input type="file" 
                                                       class="form-control form-control-lg @error('imagen') is-invalid @enderror" 
                                                       id="imagen" 
                                                       name="imagen" 
                                                       accept="image/*" 
                                                       required>
                                                <small class="form-text text-white-50">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 10MB</small>
                                                @error('imagen')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="image-preview mt-4" id="imagePreview">
                                                <img src="" alt="Vista previa" class="img-fluid rounded d-none">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="fas fa-save"></i>
                                            <span class="ms-2">Guardar Película</span>
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

.form-control, .form-select {
    background-color: var(--background-lighter);
    border: 1px solid var(--border-color);
    color: white;
}

.form-control:focus, .form-select:focus {
    background-color: var(--background-lighter);
    border-color: var(--primary-color);
    color: white;
    box-shadow: 0 0 0 0.2rem rgba(229, 9, 20, 0.25);
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.form-select option {
    background-color: var(--background-dark);
    color: white;
}

.image-preview img {
    max-height: 300px;
    object-fit: contain;
    width: 100%;
}
</style>

@push('scripts')
<script>
// Vista previa de la imagen
document.getElementById('imagen').addEventListener('change', function(e) {
    const preview = document.querySelector('#imagePreview img');
    const file = e.target.files[0];
    
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    } else {
        preview.src = '';
        preview.classList.add('d-none');
    }
});

// Validación del formulario
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endpush

@endsection 