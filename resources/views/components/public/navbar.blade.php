<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand me-auto" href="#">Logo</a>


        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('inicio') }}">Inicio</a>
                    </li>

                    <!-- Productos cientificos Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Nexus Académico
                        </a>
                        <div class="dropdown-menu custom-dropdown-menu-mega">

                            <!-- Publicaciones Dropdown -->
                            <ul>
                                <li><a class="dropdown-item disabled">Publicaciones</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('articulos.index') }}">Artículos</a></li>
                                <li><a class="dropdown-item" href="{{ route('libros.libro') }}">Libros</a></li>
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
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Eventos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('eventos.evento') }}">Eventos</a></li>
                            <li><a class="dropdown-item" href="#">Concursos</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">¿Quién soy?</a>
                    </li>
                </ul>
            </div>

        </div>

        <a href="{{ route('login') }}" class="custom-button">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span class="btn-text">Login</span>
        </a>

        <button class="navbar-toggler " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
