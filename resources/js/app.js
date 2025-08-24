import './bootstrap';
// import './validacion';
import { toggleOptions, initializeFilters } from './buscador_filtro';



document.addEventListener('DOMContentLoaded', function () {
    // Inicializar filtros solo si el formulario de búsqueda y filtrado está presente
    if (document.querySelector('.filter-form')) {
        initializeFilters();
    }
});

document.addEventListener('DOMContentLoaded', function () {

    // // Para libros
    // if (document.getElementById('file-libro') || document.getElementById('file-imagen_libro')) {
    //     modalFunctions.updateFileName('url_libro', 'file-libro', 'No se ha elegido un libro');
    //     modalFunctions.updateFileName('url_imagen_libro', 'file-imagen_libro', 'No se ha elegido una imagen');
    // }

    // // Para eventos
    // if (document.getElementById('file-imagen-evento')) {
    //     modalFunctions.updateFileName('url_imagen_evento', 'file-imagen-evento', 'No se ha elegido una imagen');
    // }

    // // Para premios
    // if (document.getElementById('file-certificado_premio') || document.getElementById('file-imagen_premio')) {
    //     modalFunctions.updateFileName('url_certificado_premio', 'file-certificado_premio', 'No se ha elegido un certificado');
    //     modalFunctions.updateFileName('url_imagen_premio', 'file-imagen_premio', 'No se ha elegido una imagen');
    // }
});

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar campos de autores para artículos y libros
    // const articuloModal = document.getElementById('articuloModal');
    // const libroModal = document.getElementById('libroModal');
    // const eventoModal = document.getElementById('eventoModal');
    // const premioModal = document.getElementById('premioModal');

    // if (libroModal) {
    //     libroModal.addEventListener('show.bs.modal', function (event) {
    //         var button = event.relatedTarget;
    //         modalFunctions.inicializarModalAutores(button, 'authorFields_libro', 'addAuthor_libro', 'removeAuthor_libro');
    //     });
    // }

    // if (eventoModal) {
    //     eventoModal.addEventListener('show.bs.modal', function (event) {
    //         var button = event.relatedTarget;
    //         modalFunctions.inicializarModalAutores(button, 'authorFields_evento', 'addAuthor_evento', 'removeAuthor_evento');
    //     });
    // }

    // if (premioModal) {
    //     premioModal.addEventListener('show.bs.modal', function (event) {
    //         var button = event.relatedTarget;
    //         modalFunctions.inicializarModalAutores(button, 'authorFields_premio', 'addAuthor_premio', 'removeAuthor_premio');
    //     });
    // }

});

// Llamar a la función para el formulario de artículos
document.addEventListener('DOMContentLoaded', function () {
    // modalFunctions.handleFormSubmission('btn_articulo', 'articuloForm');
    // modalFunctions.handleFormValidationAndSubmission('btn_articulo', 'articuloForm');
});

// temporarl
document.addEventListener("DOMContentLoaded", function (event) {

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
        dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
            dropdown.classList.toggle('active');
        });
    }

    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });

    // Manejar clics en items del dropdown
    const dropdownItems = document.querySelectorAll('.nav_dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function () {
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
    const tooltipElements = document.querySelectorAll('.nav_link[data-bs-toggle="tooltip"], .nav_dropdown-item[data-bs-toggle="tooltip"]');

    // En app.js, modifica la función updateTooltips
    // Reemplaza tu función updateTooltips con esta versión más simple
    function updateTooltips() {
        const isCollapsed = !navbar.classList.contains('show');

        tooltipElements.forEach(element => {
            const tooltip = bootstrap.Tooltip.getInstance(element);

            if (isCollapsed) {
                if (!tooltip) {
                    new bootstrap.Tooltip(element, {
                        trigger: 'hover',
                        delay: { show: 200, hide: 100 },
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

    // Your code to run since DOM is loaded and ready
});

// dom de eventos



import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

window.toggleOptions = toggleOptions;
