import './bootstrap';
import './validacion';
//import { inicializarModalAutores, updateFileName, updateFileDisplay, setModalData, setupDeleteModal, configureFormForEdit } from './modal';
import * as modalFunctions from './modal';
import { toggleOptions, initializeFilters } from './buscador_filtro';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar filtros solo si el formulario de búsqueda y filtrado está presente
    if (document.querySelector('.filter-form')) {
        initializeFilters();
    }
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
});

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar campos de autores para artículos y libros
    const articuloModal = document.getElementById('articuloModal');
    const libroModal = document.getElementById('libroModal');
    const eventoModal = document.getElementById('eventoModal');

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

import * as bootstrap from 'bootstrap'

window.toggleOptions = toggleOptions;
window.updateFileDisplay = modalFunctions.updateFileDisplay;
window.setModalData = modalFunctions.setModalData;
window.configureFormForEdit = modalFunctions.configureFormForEdit;
