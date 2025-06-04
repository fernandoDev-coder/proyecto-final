<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TakeYourSeat: reserva ya tu butaca</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Oswald:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    
    <style>
        :root {
            --navbar-height: 90px;
            --primary-color: #e50914;
            --primary-dark: #b30710;
            --secondary-color: #221f1f;
            --background-dark: #141414;
            --background-light: #1f1f1f;
            --text-light: #ffffff;
            --text-gray: #b3b3b3;
            --border-color: #404040;
        }

        body {
            margin: 0;
            padding-top: var(--navbar-height);
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-dark);
            color: var(--text-light);
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            background-color: var(--background-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar .logo h2 {
            margin: 0;
            color: var(--primary-color);
            font-family: 'Oswald', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .navbar .logo h2 i {
            font-size: 1.75rem;
            color: var(--primary-color);
        }

        .navbar .logo h2:hover {
            color: var(--primary-dark);
            transform: scale(1.02);
        }

        .nav-buttons .btn-auth {
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            height: 46px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            white-space: nowrap;
            margin: 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-light);
            text-decoration: none;
            min-width: 130px;
        }

        .nav-buttons .btn-auth:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .welcome-container {
            text-align: center;
            padding: var(--spacing-xl);
            max-width: 1200px;
            margin: 0 auto;
        }

        .header h1 {
            font-size: 3rem;
            color: var(--primary-color);
            font-family: 'Oswald', sans-serif;
            margin-bottom: 1rem;
        }

        .header p.subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            color: var(--text-gray);
        }

        .movie-banner img {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
            margin-top: 2rem;
        }

        .action-buttons {
            margin: 2rem 0;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .action-buttons .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-light);
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 1rem;
            }

            .navbar .logo h2 {
                font-size: 1.5rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .header p.subtitle {
                font-size: 1.2rem;
            }

            .action-buttons {
                flex-direction: column;
                padding: 0 1rem;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <h2><i class="fas fa-film"></i> TakeYourSeat</h2>
        </div>
        <div class="nav-buttons">
            @if(Auth::check())
                <a href="{{ route('home') }}" class="btn-auth">
                    <i class="fas fa-user"></i> Mi Cuenta
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-auth">
                    <i class="fas fa-sign-in-alt"></i> Login / Registro
                </a>
            @endif
        </div>
    </nav>

    <div class="welcome-container">
        <!-- Header -->
        <div class="header">
            <h1>¡Bienvenido/a a TakeYourSeat!</h1>
            <p class="subtitle">Reserva tus entradas para las mejores películas con facilidad.</p>
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

        <!-- Banner de cine -->
        <div class="movie-banner">
            <img src="{{ asset('images/peliculas/banner_principal.jpg') }}" alt="Banner de cine">
        </div>
    </div>

</body>

</html>