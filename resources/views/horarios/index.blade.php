<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Horarios</title>
</head>

<body>
    <h1>Lista de Horarios</h1>
    <a href="{{ route('horarios.create') }}">Agregar Horario</a>
    <ul>
        @foreach ($horarios as $horario)
            <li>Película: {{ $horario->pelicula->titulo }} - Fecha: {{ $horario->fecha }} - Hora: {{ $horario->hora }}
                <a href="{{ route('horarios.edit', $horario->id) }}">Editar</a>
                <a href="{{ route('horarios.show', $horario->id) }}">Ver</a>
            </li>
        @endforeach
    </ul>
</body>

</html>