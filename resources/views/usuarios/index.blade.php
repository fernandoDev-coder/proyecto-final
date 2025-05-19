<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
</head>

<body>
    <h1>Lista de Usuarios</h1>
    <a href="{{ route('usuarios.create') }}">Agregar Usuario</a>
    <ul>
        @foreach ($usuarios as $usuario)
            <li>{{ $usuario->name }} - {{ $usuario->email }}
                <a href="{{ route('usuarios.edit', $usuario->id) }}">Editar</a>
                <a href="{{ route('usuarios.show', $usuario->id) }}">Ver</a>
            </li>
        @endforeach
    </ul>
</body>

</html>