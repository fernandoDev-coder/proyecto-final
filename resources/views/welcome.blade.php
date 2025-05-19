<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Your Seat: reserva ya tu butaca</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Oswald:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    
    <style>
        .welcome-container {
            text-align: center;
            padding: var(--spacing-xl);
        }

        .header h1 {
            font-size: 3rem;
            color: var(--accent-color);
            font-family: var(--font-secondary);
            margin-bottom: var(--spacing-xs);
        }

        .header p.subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: var(--spacing-xl);
        }

        .movie-banner img {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: var(--shadow-lg);
        }

        .action-buttons {
            margin-top: var(--spacing-lg);
        }

        .action-buttons .btn {
            margin: var(--spacing-xs);
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <h2>Take Your Seat</h2>
        </div>
        <div class="nav-buttons">
            @if(Auth::check())
                <a href="{{ route('home') }}" class="btn btn-auth">Mi Cuenta</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-auth">Login / Registro</a>
            @endif
        </div>
    </nav>

    <div class="welcome-container">
        <!-- Header -->
        <div class="header">
            <h1>¡Bienvenido/a a Take Your Seat!</h1>
            <p class="subtitle">Reserva tus entradas para las mejores películas con facilidad.</p>
        </div>

        <!-- Banner de cine -->
        <div class="movie-banner">
            <img src="{{ asset('images/peliculas/banner_principal.jpg') }}" alt="Banner de cine">
        </div>


        <!-- Botones de acción -->
        <div class="action-buttons">
            @auth
                @if(Auth::user()->is_admin)
                    <a href="{{ route('peliculas.index') }}" class="btn btn-primary">
                        <i class="fas fa-film"></i> Gestionar Películas
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('peliculas.index') }}" class="btn btn-primary">
                        <i class="fas fa-film"></i> Ver Películas
                    </a>
                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-ticket-alt"></i> Mis Reservas
                    </a>
                @endif
            @else
                <a href="{{ route('peliculas.index') }}" class="btn btn-primary">
                    <i class="fas fa-film"></i> Ver Películas
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            @endauth
        </div>
    </div>

</body>

</html>