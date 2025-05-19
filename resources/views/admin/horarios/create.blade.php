@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card bg-dark text-white">
                <div class="card-header bg-gradient-primary">
                    <h2 class="mb-0">
                        <i class="fas fa-clock"></i> 
                        @if($peliculaSeleccionada)
                            Crear Horario para "{{ $peliculaSeleccionada->titulo }}"
                        @else
                            Crear Nuevo Horario
                        @endif
                    </h2>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.horarios.store') }}">
                        @csrf

                        <div class="mb-4">
                            @if($peliculaSeleccionada)
                                <input type="hidden" name="pelicula_id" value="{{ $peliculaSeleccionada->id }}">
                                <div class="alert alert-info">
                                    <i class="fas fa-film"></i> Película seleccionada: {{ $peliculaSeleccionada->titulo }}
                                </div>
                            @else
                                <label for="pelicula_id" class="form-label">Película</label>
                                <select class="form-select bg-dark text-white" id="pelicula_id" name="pelicula_id" required>
                                    <option value="">Selecciona una película</option>
                                    @foreach($peliculas as $pelicula)
                                        <option value="{{ $pelicula->id }}">{{ $pelicula->titulo }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control bg-dark text-white" id="fecha" name="fecha" 
                                   min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="hora" class="form-label">Hora</label>
                            <select class="form-select bg-dark text-white" id="hora" name="hora" required>
                                <option value="">Selecciona una hora</option>
                                @foreach(['15:00', '17:30', '20:00', '22:30'] as $hora)
                                    <option value="{{ $hora }}">{{ $hora }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="sala" class="form-label">Sala</label>
                            <select class="form-select bg-dark text-white" id="sala" name="sala" required>
                                <option value="">Selecciona una sala</option>
                                @foreach(['Sala 1', 'Sala 2', 'Sala 3'] as $sala)
                                    <option value="{{ $sala }}">{{ $sala }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Horario
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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

input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}

.btn {
    padding: 0.5rem 1.5rem;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.alert-info {
    background-color: rgba(52, 152, 219, 0.1);
    border-color: rgba(52, 152, 219, 0.2);
    color: #3498db;
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.alert {
    border: none;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border-left: 4px solid #28a745;
    color: #28a745;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    border-left: 4px solid #dc3545;
    color: #dc3545;
}

.alert .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.5;
}

.alert .btn-close:hover {
    opacity: 0.75;
}

.alert i {
    font-size: 1.25rem;
}
</style>
@endsection 