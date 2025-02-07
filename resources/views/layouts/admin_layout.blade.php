<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dasboard')</title>
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="admin">
    <header class="header">
        <nav class="navbar fixed-top">
            <div class="container-fluid">
                <!--<a class="navbar-brand" href="#">Offcanvas navbar</a>-->
                <button class="navbar-toggler me-left" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Dashboard</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    Subir producto cientifico
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.basurero') }}">
                                    Subir producto cientifico
                                </a>
                            </li>
                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Páginas
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Página 1</a></li>
                                    <li><a class="dropdown-item" href="#">Página 2</a></li>
                                </ul>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="main d-flex flex-column flex-grow-1">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pasar mensajes de sesión a JavaScript
            @if (session('success'))
                window.sessionMessage = "{{ session('success') }}";
            @endif
            @if (session('error'))
                window.sessionError = "{{ session('error') }}";
            @endif
        });
    </script>

</body>

</html>
