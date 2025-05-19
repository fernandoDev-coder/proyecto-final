@extends('layouts.app')

@section('content')
    <div class="reservas-container">
        <h2 class="titulo-cartelera">Horarios disponibles para "{{ $pelicula->titulo }}"</h2>

        @if($pelicula->horarios->isEmpty())
            <div class="alert-info-custom">
                No hay horarios disponibles por ahora.
            </div>
        @else
            <table class="reservas-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Sala</th>
                        <th>Elegir asiento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelicula->horarios as $horario)
                        <tr>
                            <td>{{ $horario->fecha }}</td>
                            <td>{{ $horario->hora }}</td>
                            <td>{{ $horario->sala }}</td>
                            <td>
                                @if(!empty($horario->id) && $horario->id != 0)
                                    <a href="{{ route('reservas.crearDesdeHorario', $horario->id) }}"
                                        class="btn-table btn-info">Seleccionar</a>
                                @else
                                    <span class="btn-table btn-disabled">No disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('catalogo') }}" class="btn btn-primary btn-reservar">Volver al catálogo</a>
        @endif
    </div>
@endsection