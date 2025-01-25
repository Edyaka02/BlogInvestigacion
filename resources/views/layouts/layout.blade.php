<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'RENOVATEC')</title>
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])


    <style>
        /*    main style    */
        .main {
            padding-top: 120px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body class="public d-flex flex-column min-vh-100">
    <header class="header">
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand me-auto" href="#">Logo</a>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="#">Inicio</a>
                            </li>

                            <!-- Productos cientificos Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Nexus Académico
                                </a>
                                <div class="dropdown-menu custom-dropdown-menu-mega">

                                    <!-- Publicaciones Dropdown -->
                                    <ul>
                                        <li><a class="dropdown-item disabled">Publicaciones</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#">Artículos</a></li>
                                        <li><a class="dropdown-item" href="#">Libros</a></li>
                                    </ul>

                                    <!-- Investigación Dropdown -->
                                    <ul>
                                        <li><a class="dropdown-item disabled">Investigación</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#">Proyectos de investigación</a></li>
                                        <li><a class="dropdown-item" href="#">Estancias de Investigación</a></li>
                                        <li><a class="dropdown-item" href="#">Prototipos</a></li>
                                        <li><a class="dropdown-item" href="#">Reviewer</a></li>
                                    </ul>

                                </div>

                            </li>

                            <!-- Eventos y Ponencias Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Eventos
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Eventos</a></li>
                                    <li><a class="dropdown-item" href="#">Concursos</a></li>
                                </ul>
                            </li>

                            <!-- Formación Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Formación
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Formación de Recursos Humanos</a></li>
                                    <li><a class="dropdown-item" href="#">Sobre mí</a></li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="#">Sobre mí</a>
                            </li>

                        </ul>

                    </div>

                </div>

                <a href="{{ route('login') }}" class="btn custom-btn custom-btn-login">
                    <i class="bi bi-door-open-fill"></i>Login</a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
            </div>
        </nav>
    </header>

    <!-- Contenido dinámico -->
    <main class="flex-grow-1 container mt-4 main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-body-tertiary text-center">
        <div class="footer">

            <div class="row">
                <h5>Sigueme en:</h5>
            </div>
            <div class="icon-row">
                <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-researchgate"></i></a>
            </div>
        </div>
    </footer>
</body>



</body>

</html>
