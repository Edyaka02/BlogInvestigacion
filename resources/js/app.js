import './bootstrap';
import './validacion';
import { inicializarModalAutores, updateFileName, setupDeleteModal } from './modal';
import { toggleOptions, initializeFilters } from './buscador_filtro';

document.addEventListener('DOMContentLoaded', function () {
    //initializeAuthorFields('authorFields_articulo', 'addAuthor_articulo', 'removeAuthor_articulo');

    updateFileName('url_articulo', 'file-articulo');
    updateFileName('url_imagen_articulo', 'file-imagen');
    initializeFilters(); 
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('articuloModal').addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        inicializarModalAutores(button, 'authorFields_articulo', 'addAuthor_articulo', 'removeAuthor_articulo');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    setupDeleteModal('modalEliminar', 'formEliminar', '/admin/articulos');
});

import * as bootstrap from 'bootstrap'

window.toggleOptions = toggleOptions;
