<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Your Seat - Sistema de Reservas de Cine</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Oswald:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #e50914;
            --secondary-color: #221f1f;
            --background-dark: #141414;
            --background-light: #1f1f1f;
            --background-lighter: #2a2a2a;
            --text-light: #ffffff;
            --text-gray: #b3b3b3;
            --border-color: #404040;
            --font-primary: 'Roboto', sans-serif;
            --font-secondary: 'Oswald', sans-serif;
            --spacing-xs: 0.5rem;
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --spacing-lg: 2rem;
            --spacing-xl: 3rem;
            --transition-default: all 0.3s ease;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--background-dark);
            color: var(--text-light);
            line-height: 1.6;
        }

        .navbar {
            background-color: var(--background-light);
            padding: 1rem 2rem;
            box-shadow: var(--shadow-md);
        }

        .navbar-brand {
            color: var(--primary-color);
            font-family: var(--font-secondary);
            font-size: 1.5rem;
            font-weight: 600;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: var(--transition-default);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--text-light);
            border: none;
        }

        .btn-primary:hover {
            background-color: #f40612;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--text-light);
            border: 1px solid var(--text-light);
        }

        .btn-secondary:hover {
            background-color: var(--text-light);
            color: var(--background-dark);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-name {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-name:hover {
            background-color: var(--background-light);
            color: var(--primary-color);
        }

        .user-name i {
            font-size: 1.1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .card {
            background-color: var(--background-light);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            transition: var(--transition-default);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar">
        <div class="container-fluid">
            <a href="{{ route('welcome') }}" class="navbar-brand">
                <i class="fas fa-film"></i> Take Your Seat
            </a>
            
            <div class="nav-links">
                <a href="{{ route('peliculas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-video"></i> Cartelera
                </a>
                
                @auth
                    @if(!Auth::user()->is_admin)
                        <a href="{{ route('reservas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-ticket-alt"></i> Mis Reservas
                        </a>
                    @endif
                    
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    @endif
                    
                    <div class="user-menu">
                        <a href="{{ route('usuario.perfil') }}" class="user-name">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            @if(Auth::user()->is_admin)
                                <i class="fas fa-star text-warning" title="Administrador"></i>
                            @endif
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('scripts')
</body>

</html>