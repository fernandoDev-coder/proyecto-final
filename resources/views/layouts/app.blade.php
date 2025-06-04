<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TakeYourSeat</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --navbar-height: 90px;
            --primary-color: #e50914;
            --primary-dark: #b30710;
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
            --sidebar-top-padding: 90px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            padding-top: calc(var(--navbar-height) + 1.2rem);
            min-height: 100vh;
            background-color: var(--background-dark);
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .navbar {
            height: var(--navbar-height);
            background-color: var(--background-dark);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 2.5rem;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
        }

        .navbar .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .navbar-brand {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0;
            margin: 0;
            transition: all 0.3s ease;
        }

        .navbar-brand i {
            font-size: 1.75rem;
            color: var(--primary-color);
        }

        .navbar-brand:hover {
            color: var(--primary-dark);
            transform: scale(1.02);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-links .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            white-space: nowrap;
            margin: 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-width: 130px;
            text-align: center;
            line-height: 1;
            position: relative;
        }

        .nav-links .text-white {
            font-size: 1rem;
        }

        .nav-links .fas {
            margin-right: 0.5rem;
            font-size: 1.125rem;
        }

        .ms-auto.d-flex.align-items-center.gap-2 {
            display: flex;
            align-items: center;
            gap: 0.75rem !important;
            margin: 0;
            height: 100%;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            white-space: nowrap;
            margin: 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-width: 130px;
            text-align: center;
            line-height: 1;
            position: relative;
        }

        .btn i {
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1em;
            height: 1em;
            line-height: 1;
            position: relative;
            top: 0;
        }

        .btn span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            position: relative;
            top: 0;
            flex: 0 0 auto;
        }

        .nav-links .btn, 
        .ms-auto .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: 0 1.25rem;
            height: 46px;
        }

        .btn:hover {
            transform: translateY(-1px);
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #fff;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #e50914 0%, #b30710 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #f71c27 0%, #c41016 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
        }

        .btn-secondary {
            background-color: var(--background-lighter);
            border-color: var(--border-color);
            color: var(--text-light);
        }

        .btn-secondary:hover {
            background-color: var(--background-light);
            border-color: var(--border-color);
            color: var(--text-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            border: none;
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #e35d6a 0%, #bb2d3b 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
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

        main {
            flex: 1;
            padding: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .footer {
            background-color: var(--background-light);
            border-top: 1px solid var(--border-color);
            padding: 1rem 0;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.75rem 1rem;
                height: auto;
                min-height: var(--navbar-height);
            }

            .ms-auto.d-flex.align-items-center.gap-2 {
                flex-wrap: nowrap;
                gap: 0.5rem !important;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding: 0.5rem;
                margin: 0 -0.5rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
                height: 42px;
                min-width: 120px;
            }
            
            .navbar-brand {
                font-size: 1.75rem;
                margin-right: 1rem;
            }

            .navbar-brand i {
                font-size: 1.25rem;
            }

            .btn i {
                font-size: 0.95rem;
            }
        }

        /* Ajustes para el scroll horizontal en móviles */
        .ms-auto.d-flex.align-items-center.gap-2::-webkit-scrollbar {
            height: 3px;
        }

        .ms-auto.d-flex.align-items-center.gap-2::-webkit-scrollbar-track {
            background: var(--background-dark);
        }

        .ms-auto.d-flex.align-items-center.gap-2::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }

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
            white-space: nowrap;
            color: #fff;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1em;
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Ajustes para cuando existe el sidebar */
        .has-sidebar {
            display: flex;
            width: 100%;
        }

        .has-sidebar main {
            flex: 1;
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 1rem 1rem 1rem 0.5rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 100%;
                max-width: 260px;
                z-index: 1040;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            main, .has-sidebar main {
                margin-left: 0;
                padding: 1rem;
                width: 100%;
            }

            .navbar {
                padding: 0.75rem 1rem;
            }

            .navbar-toggler {
                display: block;
                background: none;
                border: none;
                padding: 0.5rem;
                color: white;
                margin-right: 1rem;
                cursor: pointer;
            }

            .navbar-toggler:focus {
                outline: none;
                box-shadow: none;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            .ms-auto.d-flex.align-items-center.gap-2 {
                flex-wrap: nowrap;
                gap: 0.5rem !important;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding: 0.5rem;
                margin: 0 -0.5rem;
            }

            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-group .btn {
                width: 100%;
                margin: 0;
            }

            .row {
                margin-right: 0;
                margin-left: 0;
            }

            .container-fluid {
                padding-right: 1rem;
                padding-left: 1rem;
            }
        }

        /* Overlay para cerrar el menú al hacer clic fuera */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1035;
        }

        .sidebar-overlay.show {
            display: block;
        }

        @media (min-width: 768px) {
            .has-sidebar .navbar .container-fluid {
                margin-left: 260px; /* Mover el contenido de la navbar para dejar espacio al sidebar */
                width: calc(100% - 260px); /* Ajustar el ancho */
                padding-left: 1rem; /* Restaurar padding normal de Bootstrap */
                padding-right: 1rem;
            }

            .has-sidebar .navbar-brand {
                /* Asegurar visibilidad y posición */
                position: relative;
                z-index: 1045; /* Mayor que el sidebar */
            }
        }

        /* Styles for admin pages with sidebar */
        .is-admin-page .navbar-brand {
            margin-left: 260px; /* Mover a la derecha el ancho del sidebar */
        }

        @media (max-width: 767.98px) {
            /* En móviles, ocultamos el margin para no desplazarlo */
            .is-admin-page .navbar-brand {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="@if(Auth::check() && Auth::user()->is_admin && (Request::is('admin/*') || Request::is('admin'))) is-admin-page @endif">
    <nav class="navbar navbar-expand-md fixed-top">
        <div class="container-fluid">
            @if(Auth::check() && Auth::user()->is_admin && (Request::is('admin/*') || Request::is('admin')))
            <button class="navbar-toggler d-md-none" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            @endif
            @if(!Request::is('admin/dashboard'))
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-film"></i> TakeYourSeat
            </a>
            @endif
            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a class="btn btn-secondary d-inline-flex align-items-center justify-content-center" href="{{ route('peliculas.index') }}">
                        <i class="fas fa-film"></i>
                        <span>Cartelera</span>
                    </a>
                    @if(!Auth::user()->is_admin)
                        <a class="btn btn-secondary d-inline-flex align-items-center justify-content-center" href="{{ route('reservas.index') }}">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Reservas</span>
                        </a>
                    @endif
                    @if(Auth::user()->is_admin)
                        <a class="btn btn-secondary d-inline-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Admin</span>
                        </a>
                    @endif
                    <a class="btn btn-secondary d-inline-flex align-items-center justify-content-center" href="{{ route('usuario.perfil') }}">
                        <i class="fas fa-user"></i>
                        <span>Perfil</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger d-inline-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                @else
                    <a class="btn btn-primary d-inline-flex align-items-center justify-content-center" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                    <a class="btn btn-secondary d-inline-flex align-items-center justify-content-center" href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i>
                        <span>Registrarse</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    @if(Auth::check() && Auth::user()->is_admin && (Request::is('admin/*') || Request::is('admin')))
    <div class="has-sidebar">
        <main>
            @yield('content')
        </main>
    </div>
    @else
    <main>
        @yield('content')
    </main>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Confirm Dialog -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('scripts')

    <!-- Overlay para cerrar el menú -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script>
        // Control de la barra lateral en móviles
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle && sidebar) {
            const toggleSidebar = () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            };

            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });

            overlay.addEventListener('click', () => {
                toggleSidebar();
            });

            // Cerrar al cambiar el tamaño de la ventana
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768 && sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
            });
        }
    </script>
</body>

</html>