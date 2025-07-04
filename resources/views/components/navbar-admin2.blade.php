{{-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\components\navbar-admin2.blade.php --}}
{{-- <div class="menu-dashboard">
    <!-- TOP MENU -->
    <div class="top-menu mb-3">
        <div class="logo">
            <img src="./img/logo.svg" alt="">
            <span>TuMejorWeb</span>
        </div>
        <div class="toggle">
            <i class="fa-solid fa-bars"></i>
        </div>
    </div>
    <!-- MENU -->
    <div class="menu">
        <div class="enlace">
            <i class="fa-solid fa-border-all"></i>
            <span>Dashboard</span>
        </div>

        <div class="enlace">
            <i class="fa-solid fa-user"></i>
            <span>Usuarios</span>
        </div>

        <div class="enlace">
            <i class="fa-solid fa-chart-line"></i>
            <span>Analíticas</span>
        </div>

        <div class="enlace">
            <i class="fa-solid fa-message"></i>
            <span>Mensajes</span>
        </div>

        <div class="enlace">
            <i class="fa-solid fa-file"></i>
            <span>Archivos</span>
        </div>

        <div class="enlace">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Pedidos</span>
        </div>

        <div class="enlace">
            <i class="fa-solid fa-heart"></i>
            <span>Favoritos</span>
        </div>

        <div class="enlace">
            <i class="fa-solid fa-gear"></i>
            <span>Configuración</span>
        </div>
    </div>
</div> --}}

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
            <a href="{{ route('admin.dashboard') }}" class="nav_logo">
                <i class='fa-solid fa-layer-group nav_logo-icon'></i>
                <span class="nav_logo-name">BBBootstrap</span> </a>
            <div class="nav_list">
                <a href="{{ route('admin.dashboard') }}" class="nav_link active" aria-label="Dashboard">
                    <i class='fa-solid fa-border-all nav_icon'></i>
                    <span class="nav_name">Dashboard</span>
                </a>
                <a href="#" class="nav_link">
                    <i class='fa-solid fa-user nav_icon'></i>
                    <span class="nav_name">Users</span>
                </a>
                <a href="#" class="nav_link">
                    <i class='fa-solid fa-gear nav_icon'></i>
                    <span class="nav_name">Configuraciones</span>
                </a>
                <a href="#" class="nav_link">
                    <i class='bx bx-bookmark nav_icon'></i>
                    <span class="nav_name">Bookmark</span>
                </a>
                <a href="#" class="nav_link">
                    <i class='bx bx-folder nav_icon'></i>
                    <span class="nav_name">Files</span>
                </a>
                <a href="#" class="nav_link">
                    <i class='bx bx-bar-chart-alt-2 nav_icon'></i>
                    <span class="nav_name">Stats</span>
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
