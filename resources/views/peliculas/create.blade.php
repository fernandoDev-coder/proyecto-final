@extends('layouts.app')

@section('content')
<div class="container fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h2 class="mb-0"><i class="fas fa-film"></i> Añadir Nueva Película</h2>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.peliculas.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control bg-dark text-white" id="titulo" name="titulo" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control bg-dark text-white" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select bg-dark text-white" id="genero" name="genero" required>
                                    <option value="">Selecciona un género</option>
                                    <option value="Acción">Acción</option>
                                    <option value="Aventura">Aventura</option>
                                    <option value="Comedia">Comedia</option>
                                    <option value="Drama">Drama</option>
                                    <option value="Terror">Terror</option>
                                    <option value="Ciencia Ficción">Ciencia Ficción</option>
                                    <option value="Romántica">Romántica</option>
                                    <option value="Documental">Documental</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="duracion" class="form-label">Duración (minutos)</label>
                                <input type="number" class="form-control bg-dark text-white" id="duracion" name="duracion" min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="clasificacion" class="form-label">Clasificación</label>
                            <select class="form-select bg-dark text-white" id="clasificacion" name="clasificacion" required>
                                <option value="">Selecciona una clasificación</option>
                                <option value="G">G (Público General)</option>
                                <option value="PG">PG (Guía Parental)</option>
                                <option value="PG-13">PG-13 (Guía Parental Estricta)</option>
                                <option value="R">R (Restringido)</option>
                                <option value="NC-17">NC-17 (Solo Adultos)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen de la Película</label>
                            <input type="file" class="form-control bg-dark text-white" id="imagen" name="imagen" accept="image/*" required>
                            <div class="form-text text-white-50">Sube una imagen en formato JPG, PNG o GIF</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Película
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-control, .form-select {
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-control:focus, .form-select:focus {
    background-color: #2c3e50;
    border-color: #3498db;
    box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    color: white;
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.5);
}
</style>
@endsection