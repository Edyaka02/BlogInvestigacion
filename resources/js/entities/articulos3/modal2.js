// ✅ CREAR: resources/js/entities/articulos/modal2.js
import {
    inicializarModalAutores,
    updateFileName,
    updateFileDisplay,
    setModalData,
    configureFormForEdit,
    configureFormForCreate
} from '../../modal.js';

import { AjaxFormController2 } from '../../shared/utils/ajaxFormController2.js';

export function initArticuloModalHandlers2() {
    console.log('🎯 Inicializando modal con Sonner...');

    // Configurar archivos
    if (document.getElementById('file-articulo') || document.getElementById('file-imagen')) {
        updateFileName('url_articulo', 'file-articulo', 'No se ha elegido un artículo');
        updateFileName('url_imagen_articulo', 'file-imagen', 'No se ha elegido una imagen');
    }

    // Configurar modal de autores
    const articuloModal = document.getElementById('articuloModal');
    if (articuloModal) {
        articuloModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            inicializarModalAutores(button, 'authorFields_articulo', 'addAuthor_articulo', 'removeAuthor_articulo');
        });
    }

    // ✅ CONTROLADOR: Con Sonner
    const formController = new AjaxFormController2('articuloForm', 'btn_modal', 'articulos', {
        modalId: 'articuloModal',
        showSuccessToast: false,
        reloadAfterSuccess: true,
        persistMessage: true
    });
    
    formController.init();

    // Funciones globales
    window.updateFileDisplay = updateFileDisplay;
    window.setModalData = setModalData;
    window.configureFormForEdit = configureFormForEdit;
    window.configureFormForCreate = configureFormForCreate;
}