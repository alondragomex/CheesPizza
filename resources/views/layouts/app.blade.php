<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cheese Pizza 🍕 - Sabor Premium al Instante')</title>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Icons (Lucide Icons via CDN for modern minimal look) -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="nav-container">
            <a href="{{ route('menu') }}" class="logo">
                Cheese<span>Pizza</span>
            </a>
            
            <nav class="nav-links">
                <a href="{{ route('menu') }}" class="nav-link {{ Route::is('menu') ? 'active' : '' }}">
                    Menú
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn-admin {{ Route::is('admin.*') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" style="display:inline-block; width:16px; height:16px; vertical-align:middle; margin-right:4px;"></i>
                    Panel Admin
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        <!-- Alerts for feedback -->
        @if(session('success'))
            <div class="alert alert-success">
                <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="nav-container" style="flex-direction: column; gap: 1rem;">
            <p style="font-family: var(--font-serif); font-size: 1.2rem; color: var(--text-main);">
                Cheese<span>Pizza</span> - Calidad Artesanal
            </p>
            <p>© {{ date('Y') }} Cheese Pizza. Todos los derechos reservados. Hecho con ❤️ para tu Tarea de Laravel.</p>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    <!-- Custom Scripts Section -->
    @yield('scripts')
</body>
</html>
