// ✅ MODIFICAR: resources/js/entities/articulos/modal.js
import {
    inicializarModalAutores,
    updateFileName,
    updateFileDisplay,
    setModalData,
    configureFormForEdit,
    configureFormForCreate
} from '../../modal.js';

import { AjaxFormController } from '../../shared/utils/ajaxFormController.js';


export function initArticuloModalHandlers() {
    console.log('🔄 Inicializando handlers del modal de artículo...');

    // Configurar archivos específicos de artículo
    if (document.getElementById('file-articulo') || document.getElementById('file-imagen')) {
        updateFileName('url_articulo', 'file-articulo', 'No se ha elegido un artículo');
        updateFileName('url_imagen_articulo', 'file-imagen', 'No se ha elegido una imagen');
    }

    // Configurar modal de autores para artículos
    const articuloModal = document.getElementById('articuloModal');
    if (articuloModal) {
        articuloModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            inicializarModalAutores(button, 'authorFields_articulo', 'addAuthor_articulo', 'removeAuthor_articulo');
        });
    }

    // // Configurar controlador AJAX reutilizable
    // const formController = new AjaxFormController('articuloForm', 'btn_modal', 'articulos', {
    //     modalId: 'articuloModal',
    //     showSuccessToast: true, // Mostrar toast inmediato
    //     reloadAfterSuccess: true,
    //     persistMessage: true // Guardar mensaje para después de recarga
    // });
    
    // formController.init();

    console.log('✅ Handlers del modal de artículo inicializados');

    // Hacer funciones disponibles globalmente para este módulo
    window.updateFileDisplay = updateFileDisplay;
    window.setModalData = setModalData;
    window.configureFormForEdit = configureFormForEdit;
    window.configureFormForCreate = configureFormForCreate;
}