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
            <a {{-- href="{{ route('admin.dashboard') }}"  --}} class="nav_logo">
                <i class="fa-solid fa-brain nav_logo-icon"></i>
                <span class="nav_logo-name">LumenFolio</span>
            </a>
            <div class="nav_list">
                <a href="{{ route('admin.dashboard') }}" class="nav_link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    aria-label="Dashboard">
                    <i class="fa-solid fa-house nav_icon"></i>
                    <span class="nav_name">Dashboard</span>
                </a>

                <!-- Dropdown Catálogo -->
                <div class="nav_dropdown">
                    <a href="#"
                        class="nav_dropdown-toggle {{ request()->routeIs('admin.articulos.*') ? 'active' : '' }}"
                        data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Catálogo">
                        <i class='fa-solid fa-list nav_icon'></i>
                        <span class="nav_name">Catálogo</span>
                        <i class='fa-solid fa-chevron-down nav_dropdown-icon'></i>
                    </a>
                    <div class="nav_dropdown-content">
                        <a href="{{ route('admin.articulos.index') }}"
                            class="nav_dropdown-item {{ request()->routeIs('admin.articulos.*') ? 'active' : '' }}"
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
                <a href="#" class="nav_link" data-bs-toggle="tooltip" data-bs-placement="right"
                    data-bs-title="Configuraciones">
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

@push('scripts')
    <script>
        // temporarl
        document.addEventListener("DOMContentLoaded", function(event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)

                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show')

                        // change icon
                        toggle.classList.toggle('fa-xmark')
                        // add padding to body
                        bodypd.classList.toggle('body-pd')
                        // add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink))

            /*===== DROPDOWN FUNCTIONALITY =====*/
            const dropdownToggle = document.querySelector('.nav_dropdown-toggle');
            const dropdown = document.querySelector('.nav_dropdown');

            if (dropdownToggle && dropdown) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                });
            }

            // Cerrar dropdown al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (dropdown && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });

            // Manejar clics en items del dropdown
            const dropdownItems = document.querySelectorAll('.nav_dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remover active de todos los nav_link principales
                    linkColor.forEach(l => l.classList.remove('active'));

                    // Remover active de todos los dropdown items
                    dropdownItems.forEach(d => d.classList.remove('active'));

                    // Agregar active al item clickeado
                    this.classList.add('active');

                    // Mantener el dropdown como activo
                    if (dropdownToggle) {
                        dropdownToggle.classList.add('active');
                    }
                });
            });


            // Inicializar tooltips de Bootstrap
            const navbar = document.getElementById('nav-bar');
            const tooltipElements = document.querySelectorAll(
                '.nav_link[data-bs-toggle="tooltip"], .nav_dropdown-item[data-bs-toggle="tooltip"]');

            // Reemplaza tu función updateTooltips con esta versión más simple
            function updateTooltips() {
                const isCollapsed = !navbar.classList.contains('show');

                tooltipElements.forEach(element => {
                    const tooltip = bootstrap.Tooltip.getInstance(element);

                    if (isCollapsed) {
                        if (!tooltip) {
                            new bootstrap.Tooltip(element, {
                                trigger: 'hover',
                                delay: {
                                    show: 200,
                                    hide: 100
                                },
                                placement: 'right',
                                offset: [0, -100], // Solo un offset
                                customClass: 'sidebar-tooltip',
                                boundary: 'viewport',
                                // Eliminar popperConfig que está causando conflictos
                                template: '<div class="tooltip sidebar-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
                            });
                        }
                    } else {
                        if (tooltip) {
                            tooltip.dispose();
                        }
                    }
                });
            }

            // Ejecutar al cargar
            updateTooltips();

            // Ejecutar cuando cambie el estado del sidebar
            const toggle = document.getElementById('header-toggle');
            if (toggle) {
                toggle.addEventListener('click', () => {
                    setTimeout(updateTooltips, 100); // Pequeño delay para la transición
                });
            }
        });
    </script>
@endpush
