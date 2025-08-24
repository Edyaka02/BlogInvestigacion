<header class="header" id="header">
    <div class="header_toggle">
        <i class='fa-solid fa-bars' id="header-toggle"></i>
    </div>
    <div class="header_img">
        <img src="https://i.imgur.com/hczKIze.jpg" alt="">
    </div>
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a 
            {{-- href="{{ route('admin.dashboard') }}"  --}}
            class="nav_logo">
                {{-- <i class='fa-solid fa-layer-group nav_logo-icon'></i> --}}
                <i class="fa-solid fa-brain nav_logo-icon"></i>
                <span class="nav_logo-name">LumenFolio</span>
            </a>
            <div class="nav_list">
                <a 
                {{-- href="{{ route('admin.dashboard') }}"  --}}
                class="nav_link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" aria-label="Dashboard">
                    <i class="fa-solid fa-house nav_icon"></i>
                    <span class="nav_name">Dashboard</span>
                </a>

                <!-- Dropdown Catálogo -->
                <div class="nav_dropdown">
                    <a href="#" class="nav_dropdown-toggle {{ request()->routeIs('admin.articulos.*') ? 'active' : '' }}" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-title="Catálogo">
                        <i class='fa-solid fa-list nav_icon'></i>
                        <span class="nav_name">Catálogo</span>
                        <i class='fa-solid fa-chevron-down nav_dropdown-icon'></i>
                    </a>
                    <div class="nav_dropdown-content">
                        <a href="{{ route('admin.articulos.index') }}" class="nav_dropdown-item {{ request()->routeIs('admin.articulos.*') ? 'active' : '' }}"
                            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Artículos">
                            <i class='fa-solid fa-file-text'></i>
                            <span>Artículos</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Libros">
                            <i class='fa-solid fa-book'></i>
                            <span>Libros</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Proyectos">
                            <i class="fa-solid fa-lightbulb"></i>
                            <span>Proyectos</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Estancias">
                            <i class="fa-solid fa-earth-americas"></i>
                            <span>Estancias</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Prototipos">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span>Prototipos</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Redes">
                            <i class="fa-solid fa-globe"></i>
                            <span>Redes</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Reviewer">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>Reviewer</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Eventos">
                            <i class="fa-solid fa-calendar-day"></i>
                            <span>Eventos</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Concursos">
                            <i class="fa-solid fa-ranking-star"></i>
                            <span>Concursos</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Distinciones">
                            <i class="fa-solid fa-medal"></i>
                            <span>Distinciones</span>
                        </a>
                        <a href="#" class="nav_dropdown-item" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="Premios">
                            <i class='fa-solid fa-trophy'></i>
                            <span>Premios</span>
                        </a>

                    </div>
                </div>

                <a href="#" class="nav_link">
                    <i class='fa-solid fa-user nav_icon'></i>
                    <span class="nav_name">Users</span>
                </a>
                <a href="#" class="nav_link" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-title="Configuraciones">
                    <i class='fa-solid fa-gear nav_icon'></i>
                    <span class="nav_name">Configuraciones</span>
                </a>

            </div>
        </div>
        <a href="#" class="nav_link"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='fa-solid fa-right-from-bracket nav_icon'></i>
            <span class="nav_name">SignOut</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</div>
