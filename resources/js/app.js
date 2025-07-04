import './bootstrap';
import './validacion';
//import { inicializarModalAutores, updateFileName, updateFileDisplay, setModalData, setupDeleteModal, configureFormForEdit } from './modal';
import * as modalFunctions from './modal';
import { toggleOptions, initializeFilters } from './buscador_filtro';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
// import './public/filtro';
// import * as fondo from './fondo';
// import { cargarResultadosGenerico } from './public/components/paginacion.js';

// cargarResultadosGenerico({
//     urlBase: '/eventos/filtrar',
//     filtroFormId: 'filtro-form',
//     resultadosDivId: 'resultados',
//     key: 'eventos',
//     renderCard: evento => `
//         <div class="col-md-4 mb-4">
//             <a href="/eventos/${evento.ID_EVENTO}" class="card-link">
//                 <div class="product-card">
//                     <div class="product-card-img-wrapper">
//                         <img src="${evento.URL_IMAGEN_EVENTO}" class="product-card-img-top" alt="Imagen del evento">
//                     </div>
//                     <div class="product-card-body">
//                         <h5 class="product-card-title">
//                             ${evento.TITULO_EVENTO}
//                         </h5>
//                         <p class="product-card-date">
//                             ${evento.FECHA_EVENTO ? new Date(evento.FECHA_EVENTO).toLocaleDateString('es-ES', {
//                             weekday: 'long',
//                             year: 'numeric',
//                             month: 'long',
//                             day: 'numeric'
//                             }) : 'Fecha no disponible'}
//                         </p>
//                         <hr>
//                         <p class="product-card-text">
//                             Tipo: ${evento.TIPO_EVENTO}<br>
//                             Autores: <br>
//                             ${evento.autores.map(autor => `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`).join(', ')}
//                         </p>
//                     </div>
//                 </div>
//             </a>
//         </div>
//     `
// });

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar filtros solo si el formulario de búsqueda y filtrado está presente
    if (document.querySelector('.filter-form')) {
        initializeFilters();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Llama a la función para inicializar el fondo animado
    fondo.initializeAnimatedBackground();
});

document.addEventListener('DOMContentLoaded', function () {

    // Para artículos
    if (document.getElementById('file-articulo') || document.getElementById('file-imagen')) {
        modalFunctions.updateFileName('url_articulo', 'file-articulo', 'No se ha elegido un artículo');
        modalFunctions.updateFileName('url_imagen_articulo', 'file-imagen', 'No se ha elegido una imagen');
    }

    // Para libros
    if (document.getElementById('file-libro') || document.getElementById('file-imagen_libro')) {
        modalFunctions.updateFileName('url_libro', 'file-libro', 'No se ha elegido un libro');
        modalFunctions.updateFileName('url_imagen_libro', 'file-imagen_libro', 'No se ha elegido una imagen');
    }

    // Para eventos
    if (document.getElementById('file-imagen-evento')) {
        modalFunctions.updateFileName('url_imagen_evento', 'file-imagen-evento', 'No se ha elegido una imagen');
    }

    // Para premios
    if (document.getElementById('file-certificado_premio') || document.getElementById('file-imagen_premio')) {
        modalFunctions.updateFileName('url_certificado_premio', 'file-certificado_premio', 'No se ha elegido un certificado');
        modalFunctions.updateFileName('url_imagen_premio', 'file-imagen_premio', 'No se ha elegido una imagen');
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar campos de autores para artículos y libros
    const articuloModal = document.getElementById('articuloModal');
    const libroModal = document.getElementById('libroModal');
    const eventoModal = document.getElementById('eventoModal');
    const premioModal = document.getElementById('premioModal');

    if (articuloModal) {
        articuloModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            modalFunctions.inicializarModalAutores(button, 'authorFields_articulo', 'addAuthor_articulo', 'removeAuthor_articulo');
        });
    }

    if (libroModal) {
        libroModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            modalFunctions.inicializarModalAutores(button, 'authorFields_libro', 'addAuthor_libro', 'removeAuthor_libro');
        });
    }

    if (eventoModal) {
        eventoModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            modalFunctions.inicializarModalAutores(button, 'authorFields_evento', 'addAuthor_evento', 'removeAuthor_evento');
        });
    }

    if (premioModal) {
        premioModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            modalFunctions.inicializarModalAutores(button, 'authorFields_premio', 'addAuthor_premio', 'removeAuthor_premio');
        });
    }

});

document.addEventListener('DOMContentLoaded', function () {
    // Configuración de Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Ejemplo de notificación para verificar que toastr está funcionando
    if (window.sessionMessage) {
        toastr.success(window.sessionMessage);
    }
    // Mostrar notificación de error si hay un mensaje de error en la sesión
    if (window.sessionError) {
        toastr.error(window.sessionError);
    }

});

document.addEventListener('DOMContentLoaded', function () {
    // Configuración de modales de eliminación
    if (document.getElementById('modalEliminar')) {
        modalFunctions.setupDeleteModal('modalEliminar', 'formEliminar');
    }
});

// Llamar a la función para el formulario de artículos
document.addEventListener('DOMContentLoaded', function () {
    // modalFunctions.handleFormSubmission('btn_articulo', 'articuloForm');
    modalFunctions.handleFormValidationAndSubmission('btn_articulo', 'articuloForm');
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

    // Your code to run since DOM is loaded and ready
});

// dom de eventos



import * as bootstrap from 'bootstrap'

window.toggleOptions = toggleOptions;
window.updateFileDisplay = modalFunctions.updateFileDisplay;
window.setModalData = modalFunctions.setModalData;
window.configureFormForEdit = modalFunctions.configureFormForEdit;
