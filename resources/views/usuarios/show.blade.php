<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario</title>
</head>

<body>
    <h1>Detalles del Usuario</h1>
    <p>Nombre: {{ $usuario->name }}</p>
    <p>Email: {{ $usuario->email }}</p>
    <a href="{{ route('usuarios.index') }}">Volver</a>
</body>

</html>