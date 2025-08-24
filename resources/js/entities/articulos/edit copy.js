import { EntityConfig } from '../../shared/EntityConfig.js';
import { EntityManager } from '../../shared/EntityManager.js';
// import { inicializarModalAutores, setModalData, configureFormForEdit, configureFormForCreate, actualizarModal, setupFormSubmission, setupDeleteModal } from '../../shared/modal.js'; // ✅ AGREGAR setupFormSubmission y setupDeleteModal
import { 
    inicializarModalAutores, 
    setModalData, 
    configureFormForEdit, 
    configureFormForCreate,
    updateFileDisplay,
    actualizarModal, 
    setupFormSubmission, 
    setupDeleteModal 
} from '../../shared/modalManager.js';

/**
 * CONFIGURACIÓN ESPECÍFICA PARA ARTÍCULOS
 */
const ARTICULOS_CONFIG = EntityConfig.create({
    entityType: 'Artículo',
    entityRoute: 'articulos',
    urlBase: '/dashboard/articulos',
    modalFormId: 'articuloForm',
    modalId: 'articuloModal',
});

/**
 * CONFIGURACIÓN DE RENDERIZADO ESPECÍFICA PARA ARTÍCULOS
 */
const ARTICULOS_RENDER_CONFIG = {
    renderHeader: () => `
        <thead>
            <tr>
                <th>Título</th>
                <th>ISSN</th>
                <th>Fecha</th>
                <th>Revista</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
    `,
    renderRow: articulo => `
        <tr>
            <td>${articulo.TITULO_ARTICULO}</td>
            <td>${articulo.ISSN_ARTICULO}</td>
            <td>${articulo.FECHA_ARTICULO}</td>
            <td>${articulo.REVISTA_ARTICULO}</td>
            <td>${articulo.tipo ? articulo.tipo.NOMBRE_TIPO : ''}</td>
            <td>
                <div class="d-flex">
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-editar"
                            data-bs-toggle="modal" data-bs-target="#articuloModal"
                            onclick="window.prepararModalArticulos()"
                            data-id="${articulo.ID_ARTICULO}" 
                            data-issn="${articulo.ISSN_ARTICULO}"
                            data-titulo="${articulo.TITULO_ARTICULO}"
                            data-resumen="${articulo.RESUMEN_ARTICULO || ''}"
                            data-fecha="${articulo.FECHA_ARTICULO}"
                            data-revista="${articulo.REVISTA_ARTICULO}"
                            data-tipo="${articulo.ID_TIPO || ''}"
                            data-url-revista="${articulo.URL_REVISTA_ARTICULO || ''}"
                            data-url-articulo="${articulo.URL_ARTICULO || ''}"
                            data-url-imagen="${articulo.URL_IMAGEN_ARTICULO || ''}"
                            data-nombres-autores="${articulo.autores ? articulo.autores.map(a => a.NOMBRE_AUTOR).join(',') : ''}"
                            data-apellidos-autores="${articulo.autores ? articulo.autores.map(a => a.APELLIDO_AUTOR).join(',') : ''}"
                            data-orden-autores="${articulo.autores ? articulo.autores.map(a => a.pivot?.ORDEN_AUTOR || '').join(',') : ''}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-eliminar"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-id="${articulo.ID_ARTICULO}" 
                            data-type="artículo"
                            data-route="articulos"
                            data-name="${articulo.TITULO_ARTICULO}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    `
};

/**
 * INSTANCIA PARA ARTÍCULOS
 */
const articulosManager = new EntityManager(ARTICULOS_CONFIG);

/**
 * FUNCIÓN PARA INICIALIZAR AUTORES
 */
function inicializarAutoresArticulos() {
    const modal = document.getElementById('articuloModal');
    if (modal) {
        // ✅ BUSCAR: Elementos de autores por ID específicos
        const addAuthorButton = modal.querySelector('#addAuthor_articulo');
        const removeAuthorButton = modal.querySelector('#removeAuthor_articulo');
        const authorFields = modal.querySelector('#authorFields_articulo');

        if (addAuthorButton && authorFields) {
            // ✅ INICIALIZAR: Usando la función del modal.js principal
            inicializarModalAutores(
                addAuthorButton,
                authorFields.id || 'authorFields_articulo',
                addAuthorButton.id || 'addAuthor_articulo',
                removeAuthorButton?.id || 'removeAuthor_articulo'
            );

            console.log('✅ Sistema de autores inicializado para artículos');
        } else {
            console.warn('⚠️ No se encontraron elementos de autores en el modal');
            console.log('Elementos buscados:', {
                addAuthorButton: !!addAuthorButton,
                removeAuthorButton: !!removeAuthorButton,
                authorFields: !!authorFields
            });
        }
    }
}

/**
 * FUNCIÓN PARA CARGAR DATOS EN EL MODAL
 */
function cargarDatosModal(button) {
    const modal = document.getElementById('articuloModal');
    const form = document.getElementById('articuloForm');
    
    if (!modal || !form) return;

    const id = button.getAttribute('data-id');
    const esEdicion = !!id;
    console.log(`📝 Cargando datos para ${esEdicion ? 'editar' : 'crear'} artículo ID: ${id}`);

    // // ✅ LIMPIAR: Formulario primero
    // ModalFormCleaner.clearAll('articuloModal');

    // ✅ CONFIGURAR: Modal según sea crear o editar
    actualizarModal(esEdicion, { 
        entidad: 'Artículo',
        botonId: 'btn_modal',
        iconoId: 'btn_modal_icon',
        textoId: 'btn_modal_text',
        tituloId: 'modalLabel'
    });

    if (esEdicion) {
        // ✅ MODO EDICIÓN: Cargar datos del artículo
        console.log(`📝 Cargando datos para editar artículo ID: ${id}`);

        // Configurar formulario para edición
        configureFormForEdit(form, id, 'articulos');

        // ✅ CARGAR: Datos básicos del artículo
        const datosArticulo = {
            titulo_articulo: button.getAttribute('data-titulo') || '',
            issn_articulo: button.getAttribute('data-issn') || '',
            resumen_articulo: button.getAttribute('data-resumen') || '',
            fecha_articulo: button.getAttribute('data-fecha') || '',
            revista_articulo: button.getAttribute('data-revista') || '',
            id_tipo: button.getAttribute('data-tipo') || '',
            url_revista_articulo: button.getAttribute('data-url-revista') || '',
        };

        // Aplicar datos al modal
        setModalData(modal, datosArticulo);

        // ✅ CARGAR: Archivos usando updateFileDisplay
        updateFileDisplay('file-articulo', button.getAttribute('data-url-articulo'), 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen', button.getAttribute('data-url-imagen'), 'No se ha seleccionado archivo');
        // configurarEventosArchivos();

        // ✅ CARGAR: Autores usando inicializarModalAutores directamente
        inicializarModalAutores(
            button, 
            'authorFields_articulo', 
            'addAuthor_articulo', 
            'removeAuthor_articulo'
        );

    } else {
        // ✅ MODO CREAR: Limpiar formulario
        console.log('📝 Preparando modal para crear nuevo artículo');
        
        // Configurar formulario para crear
        configureFormForCreate(form, 'articulos');

        const datosArticulo = {
            titulo_articulo: '',
            issn_articulo: '',
            resumen_articulo: '',
            fecha_articulo: '',
            revista_articulo: '',
            id_tipo: '',
            url_revista_articulo: '',
        };

        // Aplicar datos al modal
        setModalData(modal, datosArticulo);

        // Limpiar archivos
        updateFileDisplay('file-articulo', '', 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen', '', 'No se ha seleccionado archivo');

        
        // ✅ INICIALIZAR: Autores vacío usando botón simulado
        const buttonSimulado = document.createElement('button');
        buttonSimulado.setAttribute('data-nombres-autores', '');
        buttonSimulado.setAttribute('data-apellidos-autores', '');
        buttonSimulado.setAttribute('data-orden-autores', '');

        inicializarModalAutores(
            buttonSimulado, 
            'authorFields_articulo', 
            'addAuthor_articulo', 
            'removeAuthor_articulo'
        );
    }
}

/**
 * FUNCIÓN PARA LIMPIAR AUTORES DEL MODAL
 * Pasar a modalManager
 */
function limpiarAutoresModal() {
    const authorFields = document.getElementById('authorFields_articulo');
    if (authorFields) {
        authorFields.innerHTML = '';
    }
}

/**
 * FUNCIÓN PARA ACTUALIZAR DISPLAY DE ARCHIVOS
 */
// function updateFileDisplay(elementId, fileUrl, defaultMessage) {
//     const element = document.getElementById(elementId);
//     if (element) {
//         if (fileUrl && fileUrl.trim() !== '') {
//             const fileName = fileUrl.split('/').pop();
//             element.innerHTML = `<small class="text-success"><i class="fa-solid fa-check me-1"></i>${fileName}</small>`;
//         } else {
//             element.innerHTML = `<small class="text-muted">${defaultMessage}</small>`;
//         }
//     }
// }

/**
 * FUNCIÓN PARA CONFIGURAR EVENTOS DE ARCHIVOS
 */
function configurarEventosArchivos() {
    // ✅ CONFIGURAR: Eventos para mostrar nombre de archivo seleccionado
    const fileInputs = [
        { input: 'url_articulo', display: 'file-articulo', defaultMsg: 'No se ha seleccionado archivo' },
        { input: 'url_imagen_articulo', display: 'file-imagen', defaultMsg: 'No se ha seleccionado una imagen' }
    ];

    fileInputs.forEach(({ input, display, defaultMsg }) => {
        const inputElement = document.getElementById(input);
        if (inputElement) {
            inputElement.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    updateFileDisplay(display, file.name, defaultMsg);
                } 
                // else {
                //     updateFileDisplay(display, '', defaultMsg);
                // }
            });
        }
    });
}


/**
 * FUNCIÓN PARA CONFIGURAR CONTADOR DE CARACTERES
 */
function configurarContadores() {
    const resumenTextarea = document.getElementById('resumen_articulo');
    const counter = document.getElementById('resumen-counter');
    
    if (resumenTextarea && counter) {
        resumenTextarea.addEventListener('input', function() {
            const length = this.value.length;
            counter.textContent = `${length} caracteres`;
            
            if (length < 50) {
                counter.className = 'text-warning';
            } else {
                counter.className = 'text-success';
            }
        });
    }
}

/**
 * FUNCIÓN PARA RECARGAR TABLA
 */
function recargarTablaArticulos() {
    console.log('🔄 Recargando tabla de artículos...');
    
    // Usar el tablaController del EntityManager
    if (articulosManager && articulosManager.tablaController && typeof articulosManager.tablaController.recargar === 'function') {
        articulosManager.tablaController.recargar();
        console.log('✅ Tabla recargada con tablaController');
    } else {
        // Fallback: usar recargarTabla del EntityManager
        articulosManager.recargarTabla().catch(error => {
            console.error('❌ Error al recargar, usando recarga de página:', error);
            window.location.reload();
        });
    }
}

/**
 * FUNCIÓN PARA CONFIGURAR BOTÓN DEL MODAL
 */
function configurarBotonModal() {
    const btnModal = document.getElementById('btn_modal');
    const form = document.getElementById('articuloForm');

    if (btnModal && form) {
        // ✅ CLONAR: Botón para evitar event listeners duplicados
        const newBtn = btnModal.cloneNode(true);
        btnModal.parentNode.replaceChild(newBtn, btnModal);

        // ✅ AGREGAR: Event listener para enviar formulario
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔘 Botón del modal clickeado, enviando formulario...');
            
            // Disparar evento submit del formulario
            const submitEvent = new Event('submit', {
                bubbles: true,
                cancelable: true
            });
            
            form.dispatchEvent(submitEvent);
        });

        console.log('✅ Botón del modal configurado para enviar formulario');
    } else {
        console.warn('⚠️ No se encontró el botón del modal o el formulario');
    }
}

/**
 * INICIALIZACIÓN
 */
// document.addEventListener('DOMContentLoaded', function () {
//     console.log('🚀 Inicializando sistema de artículos...');

//     // ✅ INICIALIZAR: Sistema de artículos
//     const result = articulosManager.inicializar(ARTICULOS_RENDER_CONFIG);

//     // // ✅ CONFIGURAR: AJAX para crear/editar
//     // console.log('✅ AJAX configurado para formulario de artículos');
//     // setupFormSubmission('articuloForm', 'articuloModal', 'Artículo', 'articulos');

//     // // ✅ CONFIGURAR: Modal eliminar
//     // setupDeleteModal('modalEliminar', 'formEliminar');
//     // console.log('✅ Modal eliminar configurado');


//     // ✅ CONFIGURAR: Eventos de archivos
//     configurarEventosArchivos();

//     // ✅ CONFIGURAR: Contadores
//     configurarContadores();

//     // ✅ CONFIGURAR: Event listeners del modal
//     const articuloModal = document.getElementById('articuloModal');
//     if (articuloModal) {
//         // Cuando se abre el modal
//         articuloModal.addEventListener('show.bs.modal', function (event) {
//             const button = event.relatedTarget;
//             if (button) {
//                 cargarDatosModal(button);
//             }
//         });

//         // Después de que se muestre completamente
//         articuloModal.addEventListener('shown.bs.modal', function () {
//             setTimeout(() => {
//                 // inicializarAutoresArticulos();
//                 configurarBotonModal(); 
//             }, 100);
//         });

//         // Cuando se cierre el modal
//         articuloModal.addEventListener('hidden.bs.modal', function () {
//             // ModalFormCleaner.clearAll('articuloModal');
//             limpiarAutoresModal();
//             actualizarModal(false, { entidad: 'Artículo' });
//         });
//     }

//     // ✅ FUNCIONES GLOBALES
//     window.recargarTablaArticulos = recargarTablaArticulos;
//     window.dispararRecargaTabla = recargarTablaArticulos;
//     window.updateFileDisplay = updateFileDisplay;
    
//     window.prepararModalArticulos = function () {
//         console.log('🔧 Preparando modal de artículos...');
//     };

//     // ✅ FUNCIÓN DE DEBUG
//     window.debugArticulos = function () {
//         console.log('🔍 Debug del sistema de artículos:');
//         console.log('articulosManager:', articulosManager);
//         console.log('tablaController:', articulosManager?.tablaController);
        
//         const form = document.getElementById('articuloForm');
//         const btn = document.getElementById('btn_modal');
//         console.log('Elementos DOM:', { form: !!form, button: !!btn });
        
//         if (form) {
//             console.log('Form action:', form.action);
//             console.log('Form method:', form.method);
//         }
//     };

//     window.articulosManager = articulosManager;

//     console.log('🎯 Sistema de artículos inicializado correctamente');
// });

// En edit.js - SIMPLIFICAR inicialización
document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Inicializando sistema de artículos...');

    // ✅ INICIALIZAR: Sistema completo (incluye AJAX automáticamente)
    const result = articulosManager.inicializar(ARTICULOS_RENDER_CONFIG);

    // ✅ CONFIGURAR: Eventos específicos de la entidad
    configurarEventosArchivos();
    // configurarContadores();
    configurarEventosModal();

    // ✅ FUNCIONES GLOBALES: Simplificadas
    window.recargarTablaArticulos = recargarTablaArticulos;
    window.dispararRecargaTabla = recargarTablaArticulos;
    window.updateFileDisplay = updateFileDisplay;
    window.articulosManager = articulosManager;
    
    // ✅ FUNCIÓN DE DEBUG: Mejorada
    window.debugArticulos = function() {
        console.log('🔍 Debug sistema artículos:', {
            manager: articulosManager,
            ajaxConfigurado: articulosManager.ajaxConfigurado,
            tablaController: !!articulosManager.tablaController,
            funciones: {
                recargar: typeof window.recargarTablaArticulos,
                disparar: typeof window.dispararRecargaTabla
            }
        });
    };

    console.log('🎯 Sistema de artículos listo');
});

// ✅ FUNCIÓN SEPARADA: Para eventos del modal
function configurarEventosModal() {
    const articuloModal = document.getElementById('articuloModal');
    if (!articuloModal) return;

    articuloModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (button) cargarDatosModal(button);
    });

    articuloModal.addEventListener('shown.bs.modal', function() {
        setTimeout(configurarBotonModal, 100);
    });

    articuloModal.addEventListener('hidden.bs.modal', function() {
        limpiarAutoresModal();
        actualizarModal(false, { entidad: 'Artículo' });
    });
}

/**
 * EXPORTAR PARA TESTING O USO EXTERNO
 */
export { articulosManager, ARTICULOS_CONFIG, ARTICULOS_RENDER_CONFIG };